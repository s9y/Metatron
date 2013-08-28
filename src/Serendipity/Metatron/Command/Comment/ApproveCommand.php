<?php

namespace Serendipity\Metatron\Command\Comment;

use Serendipity\Metatron\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ApproveCommand
 * @package Serendipity\Metatron\Command\Comment
 */
class ApproveCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('comment:approve')
            ->setDescription('Approves one or all pending comments.')
            ->addArgument(
                'commentid',
                InputArgument::OPTIONAL,
                'ID of the comment to be approved.'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $commentId = $input->getArgument('commentid');
        $validIds = array();
        if (empty($commentId)) {
            $sql = "SELECT c.*, e.title FROM {$this->getApplication()->getConfig('dbPrefix')}comments c
                    LEFT JOIN {$this->getApplication()->getConfig('dbPrefix')}entries e ON (e.id = c.entry_id)
                    WHERE c.status = 'pending'";
            $comments = serendipity_db_query($sql , false, 'assoc');
            foreach ($comments as $comment) {
                $validIds[] = $comment['id'];
            }
        } else {
            $comments = $this->getSingleComment($commentId);
        }
        if ($comments === false || !is_array($comments)) {
            $output->writeln('<error>No pending comments found.</error>');
            return;
        }
        $this->outputTable($output, $comments);
        if (empty($commentId)) {
            /**@var \Symfony\Component\Console\Helper\DialogHelper $dialog*/
            $dialog = $this->getHelperSet()->get('dialog');
            $commentId = $dialog->askAndValidate(
                $output,
                '<question>Please enter the ID of the comment that should be approved: </question>',
                function ($answer) use ($validIds) {
                    if (!in_array($answer, $validIds)) {
                        throw new \RuntimeException('This is not a valid ID.');
                    }
                    return $answer;
                }
            );
            $comments = $this->getSingleComment($commentId);
        }
        $result = serendipity_approveComment($comments[0]['id'], $comments[0]['entry_id'], true);
        if ($result) {
            $output->writeln('<info>Comment ' . $comments[0]['id'] . ' approved.</info>');
        } else {
            $output->writeln('<error>Comment ' . $comments[0]['id'] . ' could not be approved.</error>');
        }
    }

    /**
     * @param OutputInterface $output
     * @param array $comments
     * @return void
     */
    protected function outputTable(OutputInterface $output, array $comments = array())
    {
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

    /**
     * @param $commentId
     * @return array|bool|mixed|string
     */
    protected function getSingleComment($commentId)
    {
        $sql = "SELECT c.*, e.title FROM {$this->getApplication()->getConfig('dbPrefix')}comments c
                    LEFT JOIN {$this->getApplication()->getConfig('dbPrefix')}entries e ON (e.id = c.entry_id)
                    WHERE c.status = 'pending' AND c.id = " . $commentId;
        $comments = serendipity_db_query($sql, true, 'assoc');
        return $comments;
    }
}
