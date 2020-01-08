<?php

namespace isleshocky77\DeskCom\Command;

use isleshocky77\DeskCom\Api\DeskComClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Topics extends Command
{
    protected static $defaultName = 'desk-com:topics:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = DeskComClient::getInstance();

        $uri = 'topics';
        do {
            $response = $client->get($uri, ['auth' => 'oauth']);
            $payload = json_decode((string) $response->getBody());

            $topics = $payload->_embedded->entries;

            foreach ($topics as $topic) {
                $output->writeln(sprintf('%s : %s', $topic->id, $topic->name));
            }
        } while (null !== ($uri = $payload->_links->next->href));

        return 0;
    }
}
