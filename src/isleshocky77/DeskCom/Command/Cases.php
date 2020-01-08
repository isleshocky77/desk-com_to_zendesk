<?php

namespace isleshocky77\DeskCom\Command;

use isleshocky77\DeskCom\Api\DeskComClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Cases extends Command
{
    protected static $defaultName = 'desk-com:cases:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = DeskComClient::getInstance();

        $uri = 'cases';
        do {
            $response = $client->get($uri, ['auth' => 'oauth']);
            $payload = json_decode((string) $response->getBody());

            $cases = $payload->_embedded->entries;

            foreach ($cases as $case) {
                $output->writeln(sprintf('%s : %s', $case->id, $case->subject));
            }
        } while (null !== ($uri = $payload->_links->next->href));

        return 0;
    }
}
