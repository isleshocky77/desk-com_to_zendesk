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

class TopicsSync extends Command
{
    protected static $defaultName = 'desk-com:topics:sync';

    protected function configure()
    {
        $this->addArgument('category-id', InputArgument::REQUIRED, 'The Zendesk Category to sync with Desk.com Topics as Zendesk Sections');

        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Do not execute insert / updates');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $categoryId = $input->getArgument('category-id');

        $table = new Table($output);
        $table->setHeaders(['Desk ID', 'Desk Name', 'Zendesk ID', 'Zendesk Name', 'Status']);

        $topics = DeskComClient::getInstance()->findAllTopics();

        foreach ($topics as $topic) {
            $matchingSection = ZendeskClient::getInstance()->findSectionForCategoryByName($categoryId, $topic->name);

            $zendeskSectionId = null;
            $zendeskSectionName = null;
            $zendeskSectionStatus = null;
            if (null !== $matchingSection) {
                $zendeskSectionId = $matchingSection->id;
                $zendeskSectionName = $matchingSection->name;
                $zendeskSectionStatus = 'Matched';
            } else { // Create a new Section from the Topic
                if (false === $input->getOption('dry-run')) {
                    $zendeskSection = ZendeskClient::getInstance()->createSection($categoryId, $topic->name, $topic->description, $topic->position);

                    $zendeskSectionId = $zendeskSection->id;
                    $zendeskSectionName = $zendeskSection->name;
                    $zendeskSectionStatus = 'Saved';
                } else {
                    $zendeskSectionName = $topic->name;
                    $zendeskSectionId = 'N/A (Dry Run)';
                    $zendeskSectionStatus = 'Saved (Dry Run)';
                }
            }

            $table->addRow([
                $topic->id,
                $topic->name,
                $zendeskSectionId,
                $zendeskSectionName,
                $zendeskSectionStatus,
            ]);
        }

        $table->setFooterTitle(sprintf('Total Topics : %d', count($topics)));

        $table->render();

        return 0;
    }
}
