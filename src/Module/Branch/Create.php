<?php
namespace Gbo\PhpGithubCli\Module\Branch;

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
            ->setName('branch:create')
            ->setDescription('Create a branch from another branch')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addOption(
                'target',
                't',
                InputOption::VALUE_REQUIRED,
                'The name of the branch you want to create'
            )
            ->addOption(
                'source',
                's',
                InputOption::VALUE_REQUIRED,
                'The branch to create this branch from'
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

        $reference = self::$githubClient->api('gitData')->references()->show(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            'heads/'.$input->getOption('source')
        );
        return self::$githubClient->api('gitData')->references()->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            [
                'ref' => 'refs/heads/'.$input->getOption('target'),
                'sha' =>  $reference['object']['sha']]
        );
    }
}
