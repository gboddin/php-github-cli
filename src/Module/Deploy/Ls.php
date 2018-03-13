<?php
namespace Gbo\PhpGithubCli\Module\Deploy;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Ls extends GithubCommand
{

    /**
     * Symfony cli module config
     */
    protected function githubConfigure()
    {
        $this
            ->setName('deploy:list')
            ->setDescription('List deployments for a repo')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name');
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
        return self::$githubClient->api('deployment')->all(
            $input->getArgument('org'),
            $input->getArgument('repo')
        );
    }

    protected function humanOutput(OutputInterface $output, $result)
    {
        $table = new Table($output);
        $table->setHeaders(['ID', 'Ref','Environment','User','Date']);
        foreach ($result as $deployment) {
            $table->addRow(
                [$deployment['id'],$deployment['ref'],
                    $deployment['environment'],$deployment['creator']['login'], $deployment['created_at']]
            );
        }
        $table->render();
    }
}
