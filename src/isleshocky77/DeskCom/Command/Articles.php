<?php

namespace isleshocky77\DeskCom\Command;

use isleshocky77\DeskCom\Api\DeskComClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Articles extends Command
{
    protected static $defaultName = 'desk-com:articles:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['ID', 'Subject', 'Internal Notes', 'Keywords']);

        $articles = DeskComClient::getInstance()->findAllArticles();

        foreach ($articles as $article) {
            $table->addRow([
                $article->id, $article->subject,
                $article->internal_notes,
                $article->keywords,
            ]);
        }

        $table->setFooterTitle(sprintf('Total Articles : %d', count($articles)));

        $table->render();

        return 0;
    }
}
