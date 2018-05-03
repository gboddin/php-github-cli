<?php
namespace Gbo\PhpGithubCli\Module\Status;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Set extends GithubCommand
{

    /**
     * Symfony cli module config
     */
    protected function githubConfigure()
    {
        $this
            ->setName('status:set')
            ->setDescription('Creates/updates a commit status')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addArgument('sha', InputArgument::REQUIRED, 'Commit SHA')
            ->addOption('state', 's', InputOption::VALUE_REQUIRED, 'State')
            ->addOption('context', 'c', InputOption::VALUE_REQUIRED, 'Context')
            ->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'Short description')
            ->addOption('target_url', 't', InputOption::VALUE_REQUIRED, 'Target URL')
            ->addOption('avatar_url', 'a', InputOption::VALUE_REQUIRED, 'Avatar URL')
        ;
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
        return self::$githubClient->repos()->statuses()->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getArgument('sha'),
            $input->getOptions()
        );
    }
}
