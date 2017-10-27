<?php
namespace Gbo\PhpGithubCli\Module\Deploy;

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
    protected function configure()
    {
        $this
            ->setName('deploy:create')
            ->setDescription('Creates a deployment')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addOption('ref', 'r', InputOption::VALUE_REQUIRED, 'The ref to deploy. This can be a branch, tag, or SHA')
            ->addOption('environment', 'e', InputOption::VALUE_REQUIRED, 'Name for the target deployment environment')
            ->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'Short description of the deployment.');
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
        return self::$githubClient->api('deployment')->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getOptions()
        );
    }
}
