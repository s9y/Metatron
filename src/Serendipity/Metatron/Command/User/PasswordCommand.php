<?php

namespace Serendipity\Metatron\Command\User;

use Serendipity\Metatron\Command\CommonCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class PasswordCommand
 * @package Serendipity\Metatron\Command\User
 */
class PasswordCommand extends CommonCommand
{
    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('user:password')
            ->setDescription('Set new password for user.')
            ->addArgument(
                'username',
                InputArgument::REQUIRED,
                'Author name or author ID'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @throws \RuntimeException
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
            $author = serendipity_fetchAuthor($username);
        } else {
            $author = serendipity_fetchUsers($userid);
        }
        if (!is_array($author)) {
            throw new \RuntimeException('User not found!');
        } else {
            $author = array_shift($author);
        }
        /**@var \Symfony\Component\Console\Helper\DialogHelper $dialog*/
        $dialog = $this->getHelperSet()->get('dialog');
        $newPassword = $dialog->askHiddenResponse(
            $output,
            'Please enter the new password: '
        );
        $newPasswordRepeat = $dialog->askHiddenResponse(
            $output,
            'Please enter the new password: '
        );
        if ($newPassword !== $newPasswordRepeat) {
            throw new \RuntimeException('Passwords do not match. Please try again.');
        }
        serendipity_set_user_var('password', $newPassword, $author['authorid'], false);
        $output->writeln('New password set for user "' . $author['username'] . '".');
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
