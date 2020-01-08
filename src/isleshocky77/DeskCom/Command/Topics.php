<?php

namespace isleshocky77\DeskCom\Command;

use isleshocky77\DeskCom\Api\DeskComClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Topics extends Command
{
    protected static $defaultName = 'desk-com:topics:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = DeskComClient::getInstance();

        $table = new Table($output);
        $table->setHeaders(['Desk ID', 'Desk Name', 'Zendesk ID', 'Zendesk Name', 'Status']);

        $uri = 'topics';
        do {
            $response = $client->get($uri, ['auth' => 'oauth']);
            $payload = json_decode((string) $response->getBody());

            $topics = $payload->_embedded->entries;

            foreach ($topics as $topic) {
                $table->addRow([
                    $topic->id, $topic->name,
                ]);
            }
        } while (null !== ($uri = $payload->_links->next->href));

        $table->setFooterTitle(sprintf('Total Topics : %d', $payload->total_entries));

        $table->render();

        return 0;
    }
}
