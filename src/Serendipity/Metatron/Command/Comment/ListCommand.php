<?php

namespace Serendipity\Metatron\Command\Comment;

use Serendipity\Metatron\Command\CommonCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 * @package Serendipity\Metatron\Command\Comment
 */
class ListCommand extends CommonCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('comment:list')
            ->setDescription('Lists all comments.')
            ->addArgument(
                'limit',
                InputArgument::OPTIONAL,
                'Limit the result to the latest X comments.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getArgument('limit');
        $sql = "SELECT c.*, e.title FROM {$this->getApplication()->getConfig('dbPrefix')}comments c
                LEFT JOIN {$this->getApplication()->getConfig('dbPrefix')}entries e ON (e.id = c.entry_id)
                ORDER BY c.id DESC LIMIT " . ($limit ? $limit : 20);
        $comments = serendipity_db_query($sql , false, 'assoc');
        /**@var \Symfony\Component\Console\Helper\TableHelper $table*/
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table
            ->setHeaders(array('ID', 'Entry title', 'Date', 'Author', 'E-Mail', 'Comment Body', 'Type', 'Status'))
            ->setRows($this->getTableData($comments));
        $table->render($output);
    }

    /**
     * @param array $comments
     * @return array
     */
    protected function getTableData(array $comments = array())
    {
        $substrLength = 50;
        $tableData = array();
        foreach ($comments as $key => $comment) {
            $tableData[$key] = array(
                'id' => $comment['id'],
                'title' => $comment['title'],
                'date' => strftime('%b %e %Y, %H:%M', (int) $comment['timestamp']),
                'author' => $comment['author'],
                'email' => $comment['email'],
                'body' => mb_substr($comment['body'], 0, $substrLength) . ((mb_strlen($comment['body']) > $substrLength) ? '…' : ''),
                'type' => $comment['type'],
                'status' => $comment['status'],
            );
        }
        return $tableData;
    }
}
