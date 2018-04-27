<?php
namespace Gbo\PhpGithubCli\Module\Repo;

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
            ->setName('repo:search')
            ->setDescription('Search repositories')
            ->addArgument('search', InputArgument::REQUIRED, 'Search query (eg: org:ec-europa)');
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
        $paginator  = new \Github\ResultPager(self::$githubClient);
        return $paginator->fetchAll(
            self::$githubClient->search(),
            'repositories',
            [$input->getArgument('search')]
        );
    }

    protected function humanOutput(OutputInterface $output, $result)
    {
        $table = new Table($output);
        $table->setHeaders(['Org', 'Name']);
        foreach ($result as $repo) {
            $table->addRow(
                [$repo['owner']['login'], $repo['name']]
            );
        }
        $table->render();
    }

    protected function csvOutput(OutputInterface $output, $result)
    {
        foreach ($result as $repo) {
            $output->writeln(
                implode("\t", [
                    $repo['owner']['login'],
                    $repo['name']
                ])
            );
        }
    }
}
