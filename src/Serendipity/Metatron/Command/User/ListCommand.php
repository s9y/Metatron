<?php

namespace Serendipity\Metatron\Command\User;

use Serendipity\Metatron\Command\AbstractCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ListCommand
 * @package Serendipity\Metatron\Command\User
 */
class ListCommand extends AbstractCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('user:list')
            ->setDescription('Lists all users in the system.')
            ->addArgument(
                'username',
                InputArgument::OPTIONAL,
                'Author name or author ID'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $username = $input->getArgument('username');
        $userid = null;
        if (is_numeric($username)) {
            $userid = $username;
            $username = null;
        }
        if ($username) {
            $result = serendipity_fetchAuthor($username);
            $author = array_shift($result);
            $rows[] = array(
                $author['authorid'],
                $author['username'],
                $author['realname'],
                $author['email'],
                $this->getPermissionLevelName($author['userlevel']),
            );
        } else {
            $authors = serendipity_fetchUsers($userid);
            $rows = array();
            foreach ($authors as $author) {
                $rows[] = array(
                    $author['authorid'],
                    $author['username'],
                    $author['realname'],
                    $author['email'],
                    $this->getPermissionLevelName($author['userlevel']),
                );
            }
        }
        /**@var \Symfony\Component\Console\Helper\TableHelper $table*/
        $table = $this->getApplication()->getHelperSet()->get('table');
        $table
            ->setHeaders(array('ID', 'Username', 'Real Name', 'E-Mail', 'User Level'))
            ->setRows($rows);
        $table->render($output);
    }

    /**
     * @return mixed
     */
    protected function fetchAuthors()
    {
        $prefix = $this->getApplication()->getConfig('dbPrefix');
        $conn = $this->getConn();
        $sql = "SELECT * FROM " . $prefix . "authors";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $authors = $stmt->fetchAll();
        return $authors;
    }

    /**
     * @param int $level
     * @return string
     */
    protected function getPermissionLevelName($level)
    {
        $permissionLevels = array(
            0 => USERLEVEL_EDITOR_DESC,
            1 => USERLEVEL_CHIEF_DESC,
            255 => USERLEVEL_ADMIN_DESC
        );
        return $permissionLevels[$level];
    }
}
