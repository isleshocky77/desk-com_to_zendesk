<?php

namespace isleshocky77\DeskCom\Command;

use isleshocky77\DeskCom\Api\DeskComClient;
use isleshocky77\Zendesk\Api\ZendeskClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ArticlesSync extends Command
{
    protected static $defaultName = 'desk-com:articles:sync';

    protected function configure()
    {
        $this->addArgument('category-id', InputArgument::REQUIRED, 'The Zendesk Category to sync with Desk.com Topics as Zendesk Articles');

        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not execute insert / updates');
        $this->addOption('user-segment-id', null, InputOption::VALUE_REQUIRED, 'The user segment which can see the article');
        $this->addOption('permission-group-id', null, InputOption::VALUE_REQUIRED, 'The group who has permission to update articles');

        $this->addOption('filter-subject', null, InputOption::VALUE_OPTIONAL, 'A Desk.com Article Subject to filter updates to');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $categoryId = $input->getArgument('category-id');

        $table = new Table($output);
        $table->setHeaders(['Desk ID', 'Desk Subject', 'Zendesk ID', 'Zendesk Name', 'Status']);

        $articles = DeskComClient::getInstance()->findAllArticles();

        foreach ($articles as $article) {
            $filterSubject = $input->getOption('filter-subject');
            if ($filterSubject && $article->subject !== $filterSubject) {
                continue;
            }

            $topic = DeskComClient::getInstance()->getTopicForArticle($article);

            $matchingSection = ZendeskClient::getInstance()->findSectionForCategoryByName($categoryId, $topic->name);
            if (!$matchingSection instanceof \stdClass) {
                throw new \RuntimeException(sprintf('Section for topic "%s" should aleady exist, sync topics', $topic->name));
            }
            $matchingArticle = ZendeskClient::getInstance()->findArticleForSectionByTitle($matchingSection, $article->subject);

            $zendeskArticleId = null;
            $zendeskArticleTitle = null;
            $zendeskArticleStatus = null;

            if (null !== $matchingArticle) {
                $zendeskArticleId = $matchingArticle->id;
                $zendeskArticleTitle = $matchingArticle->title;
                $zendeskArticleStatus = 'Matched';
            } else { // Create a new Article
                if (false === $input->getOption('dry-run')) {
                    $zendeskArticle = ZendeskClient::getInstance()->createArticle(
                        $matchingSection->id,
                        $article->subject,
                        $article->body,
                        explode(', ', $article->keywords),
                        $input->getOption('user-segment-id'),
                        $input->getOption('permission-group-id')
                    );

                    $zendeskArticleId = $zendeskArticle->id;
                    $zendeskArticleTitle = $zendeskArticle->title;
                    $zendeskArticleStatus = 'Saved';
                } else {
                    $zendeskArticleTitle = $article->subject;
                    $zendeskArticleId = 'N/A (Dry Run)';
                    $zendeskArticleStatus = 'Saved (Dry Run)';
                }
            }

            $table->addRow([
                $article->id,
                $article->subject,
                $zendeskArticleId,
                $zendeskArticleTitle,
                $zendeskArticleStatus,
            ]);
        }

        $table->setFooterTitle(sprintf('Total Articles : %d', count($articles)));

        $table->render();

        return 0;
    }

    private function updateNoteForId(string $deskComArticleId, string $zendeskArticleId)
    {
        $client = DeskComClient::getInstance();
        $response = $client->patch('articles/'.$deskComArticleId, [
            'json' => [
                'internal_notes' => 'This is what it is',
            ],
            'auth' => 'oauth',
        ]);
    }
}
