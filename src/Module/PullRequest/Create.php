<?php
namespace Gbo\PhpGithubCli\Module\PullRequest;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Create extends GithubCommand
{

    /**
     * Symfony cli module config
     */
    protected function githubConfigure()
    {
        $this
            ->setName('pr:create')
            ->setDescription('Create a pull request')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addOption(
                'title',
                't',
                InputOption::VALUE_REQUIRED,
                'The title of the pull request'
            )
            ->addOption(
                'head',
                's',
                InputOption::VALUE_REQUIRED,
                'The name of the branch where your changes are implemented. 
            For cross-repository pull requests in the same network, 
            namespace head with a user like this: <info>username:branch</info>'
            )
            ->addOption(
                'base',
                'b',
                InputOption::VALUE_REQUIRED,
                'The name of the branch you want the changes pulled into.
             This should be an existing branch on the current repository. <comment>
            You cannot submit a pull request to one repository that requests a merge to a base of another repository.'.
                '</comment>'
            )
            ->addOption(
                'body',
                'd',
                InputOption::VALUE_REQUIRED,
                'The contents of the pull request.'
            );
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
        return self::$githubClient->pullRequests()->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getOptions()
        );
    }
}
