<?php
namespace Gbo\PhpGithubCli\Module\PullRequest;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Comment extends GithubCommand
{
    protected function configure()
    {
        $this
            ->setName('pr:comment')
            ->setDescription('Comment a pull request')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addArgument('pr', InputArgument::REQUIRED, 'PR number')
            ->addArgument('comment', InputArgument::REQUIRED, 'Comment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        var_dump(self::$githubClient->issues()->comments()->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getArgument('pr'),
            ['body' => $input->getArgument('comment')]
        ));
    }
}
