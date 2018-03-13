<?php
namespace Gbo\PhpGithubCli\Module\Ref;

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
            ->setName('ref:create')
            ->setDescription('Create a branch from another ref')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addOption(
                'ref',
                'r',
                InputOption::VALUE_REQUIRED,
                'The name of the fully qualified reference (ie: <info>refs/heads/master</info>). 
            If it doesn\'t start with \'refs\' and have at least two slashes, it will be rejected.'
            )
            ->addOption(
                'sha',
                's',
                InputOption::VALUE_REQUIRED,
                'The SHA1 value to set this reference to'
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
        return self::$githubClient->api('gitData')->references()->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $input->getOptions()
        );
    }
}
