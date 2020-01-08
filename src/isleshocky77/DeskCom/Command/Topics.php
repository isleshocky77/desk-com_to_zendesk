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
        $table = new Table($output);
        $table->setHeaders(['ID', 'Name']);

        $topics = DeskComClient::getInstance()->findAllTopics();

        foreach ($topics as $topic) {
            $table->addRow([
                $topic->id, $topic->name,
            ]);
        }

        $table->setFooterTitle(sprintf('Total Topics : %d', count($topics)));

        $table->render();

        return 0;
    }
}
