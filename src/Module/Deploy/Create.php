<?php
namespace Gbo\PhpGithubCli\Module\Deploy;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Exception\InvalidOptionException;
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
            ->setName('deploy:create')
            ->setDescription('Creates a deployment')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addOption('ref', 'r', InputOption::VALUE_REQUIRED, 'The ref to deploy. This can be a branch, tag, or SHA')
            ->addOption('environment', 'e', InputOption::VALUE_REQUIRED, 'Name for the target deployment environment')
            ->addOption('description', 'd', InputOption::VALUE_REQUIRED, 'Short description of the deployment.')
            ->addOption('task', 't', InputOption::VALUE_REQUIRED, 'Specifies a task to execute.', 'deploy')
            ->addOption(
                'auto_merge',
                'm',
                InputOption::VALUE_NONE,
                'Attempts to automatically merge the default branch into the requested ref, 
                if it is behind the default branch.'
            )
            ->addOption(
                'required_contexts',
                'c',
                InputOption::VALUE_IS_ARRAY + InputOption::VALUE_REQUIRED,
                'The status contexts to verify against commit status checks. By default all.',
                null
            )
            ->addOption('skip_checks', 's', InputOption::VALUE_NONE, 'Skips all status checks');
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
        $options = $input->getOptions();
        /**
         * If we have an empty array, it means user didn't specify context.
         * We therefore unset the options so Github checks vs all contexts.
         */
        if (empty($options['required_contexts'])) {
            unset($options['required_contexts']);
        }
        /**
         * If we have skip_checks and we have a non-empty require_contexts
         * the user probably screwed its input. Dying.
         */
        if ($options['skip_checks'] && !empty($options['required_contexts'])) {
            throw new InvalidOptionException('-x cannot be used with -c');
        }
        /**
         * If we have skip_checks, set it to an empty array so Github doesn't checks
         * commit status.
         */
        if ($options['skip_checks']) {
            $options['required_contexts'] = [];
        }
        return self::$githubClient->api('deployment')->create(
            $input->getArgument('org'),
            $input->getArgument('repo'),
            $options
        );
    }

    protected function humanOutput(OutputInterface $output, $result)
    {
        $output->writeln(
            '<info>[OK]</info> Deployment #'.$result['id'].
            ' from ref '.$result['ref'].' to environment '.$result['environment'].' created.'
        );
    }
}
