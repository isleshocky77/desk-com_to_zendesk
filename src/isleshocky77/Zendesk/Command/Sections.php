<?php

namespace isleshocky77\Zendesk\Command;

use isleshocky77\Zendesk\Api\ZendeskClient;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Sections extends Command
{
    protected static $defaultName = 'zendesk:sections:list';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $client = ZendeskClient::getInstance();

        $table = new Table($output);
        $table->setHeaders(['ID', 'Parent ID', 'Name', 'Category ID']);

        $sections = $client->getAllSections();

        foreach ($sections as $section) {
            $table->addRow([
                $section->id,
                $section->parent_id,
                $section->name,
                $section->category_id
            ]);
        }

        $table->setFooterTitle(sprintf('Total Sections : %d', count($sections)));

        $table->render();

        return 0;
    }
}
