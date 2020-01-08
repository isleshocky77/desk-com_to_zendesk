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
        $client = DeskComClient::getInstance();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Subject']);

        $uri = 'articles';
        do {
            $response = $client->get($uri, ['auth' => 'oauth']);
            $payload = json_decode((string) $response->getBody());

            $articles = $payload->_embedded->entries;

            foreach ($articles as $article) {
                $table->addRow([
                    $article->id, $article->subject,
                ]);
            }
        } while (null !== ($uri = $payload->_links->next->href));

        $table->setFooterTitle(sprintf('Total Articles : %d', $payload->total_entries));

        $table->render();

        return 0;
    }
}
