<?php
namespace Gbo\PhpGithubCli\Module\File;

use Gbo\PhpGithubCli\GithubCommand;
use Github\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Update extends GithubCommand
{

    /**
     * Symfony cli module config
     */
    protected function githubConfigure()
    {
        $this
            ->setName('file:update')
            ->setDescription('Updates a file')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addArgument('path', InputArgument::REQUIRED, 'The content path.')
            ->addArgument('message', InputArgument::REQUIRED, 'The commit message.')
            ->addArgument('content', InputArgument::REQUIRED, 'The content of the file')
            ->addArgument('branch', InputArgument::REQUIRED, 'Branch to update the file in')
            ->addOption('create', 'c', InputOption::VALUE_NONE, 'Create file if not found');
    }

    /**
     * githubExec implementation
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return array
     * @throws \Github\Exception\MissingArgumentException
     */
    protected function githubExec(InputInterface $input, OutputInterface $output)
    {

        if (self::$githubClient->api('repo')->contents()->exists(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getArgument('path'),
            $input->getArgument('branch')
        )) {
            $oldFile = self::$githubClient->api('repo')->contents()->show(
                $input->getArgument('org'),
                $input->getArgument('repo'),
                $input->getArgument('path'),
                $input->getArgument('branch')
            );

            return self::$githubClient->api('repo')->contents()->update(
                $input->getArgument('org'),
                $input->getArgument('repo'),
                $input->getArgument('path'),
                $input->getArgument('content'),
                $input->getArgument('message'),
                $oldFile['sha'],
                $input->getArgument('branch')
            );
        } elseif ($input->getOption('create')) {
            return self::$githubClient->api('repo')->contents()->create(
                $input->getArgument('org'),
                $input->getArgument('repo'),
                $input->getArgument('path'),
                $input->getArgument('content'),
                $input->getArgument('message'),
                $input->getArgument('branch')
            );
        } else {
            throw new \Exception('File doesn\'t exists');
        }
    }
}
