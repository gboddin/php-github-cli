<?php
namespace Gbo\PhpGithubCli\Module\PullRequest;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Comment extends GithubCommand
{

    /**
     * Symfony cli module config
     */
    protected function githubConfigure()
    {
        $this
            ->setName('pr:comment')
            ->setDescription('Comment a pull request')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addArgument('pr', InputArgument::REQUIRED, 'PR number')
            ->addArgument('comment', InputArgument::REQUIRED, 'Comment');
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
        return self::$githubClient
            ->issues()
            ->comments()
            ->create(
                $input->getArgument('org'),
                $input->getArgument('repo'),
                $input->getArgument('pr'),
                ['body' => $input->getArgument('comment')]
            );
    }
}
