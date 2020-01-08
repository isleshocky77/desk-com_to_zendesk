<?php

namespace isleshocky77\Zendesk\Command;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use isleshocky77\DeskCom\Api\DeskComClient;
use isleshocky77\Zendesk\Api\ZendeskClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Articles extends Command
{
    protected static $defaultName = 'zendesk:articles:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ZendeskClient::getInstance();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name']);

        $uri = '/api/v2/help_center/en-us/articles.json';

        do {
            $response = $client->get($uri);
            $payload = json_decode((string) $response->getBody());

            $articles = $payload->articles;

            foreach ($articles as $article) {
                $table->addRow([
                    $article->id, $article->name
                ]);
            }
        } while (($uri = $payload->next_page) !== null);

        $table->setFooterTitle(sprintf('Total Articles : %d', $payload->count));

        $table->render();

        return 0;
    }
}
