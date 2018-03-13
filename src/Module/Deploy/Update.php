<?php
namespace Gbo\PhpGithubCli\Module\Deploy;

use Gbo\PhpGithubCli\GithubCommand;
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
            ->setName('deploy:update')
            ->setDescription('Creates a deployment status')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addArgument('deploy_id', InputArgument::REQUIRED, 'Deployment ID')
            ->addOption(
                'state',
                's',
                InputOption::VALUE_REQUIRED,
                'The state of the status. Can be one of error, failure, pending, or success.'
            )
            ->addOption(
                'target_url',
                't',
                InputOption::VALUE_REQUIRED,
                'The target URL to associate with this status. 
                This URL should contain output to keep the user updated while the task is running or serve as 
                historical information for what happened in the deployment.'
            )
            ->addOption(
                'description',
                'd',
                InputOption::VALUE_REQUIRED,
                'A short description of the status. Maximum length of 140 characters.'
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
        return self::$githubClient->api('deployment')->updateStatus(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getArgument('deploy_id'),
            $input->getOptions()
        );
    }
}
