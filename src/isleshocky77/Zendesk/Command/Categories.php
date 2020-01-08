<?php

namespace isleshocky77\Zendesk\Command;

use isleshocky77\Zendesk\Api\ZendeskClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Categories extends Command
{
    protected static $defaultName = 'zendesk:categories:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ZendeskClient::getInstance();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Name']);

        $categories = $client->getAllCategories();

        foreach ($categories as $category) {
            $table->addRow([
                $category->id,
                $category->name,
            ]);
        }

        $table->setFooterTitle(sprintf('Total Sections : %d', count($categories)));

        $table->render();

        return 0;
    }
}
