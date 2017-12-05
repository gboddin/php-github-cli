<?php
namespace Gbo\PhpGithubCli\Module\Branch;

use Gbo\PhpGithubCli\GithubCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Protect extends GithubCommand
{

    /**
     * Symfony cli module config
     */
    protected function githubConfigure()
    {
        $this
            ->setName('branch:protect')
            ->setDescription('Protect a branch')
            ->addArgument('org', InputArgument::REQUIRED, 'Repo owner')
            ->addArgument('repo', InputArgument::REQUIRED, 'Repo name')
            ->addArgument('branch', InputArgument::REQUIRED, 'Branch name')
            ->addOption(
                'status-check',
                's',
                InputOption::VALUE_IS_ARRAY + InputOption::VALUE_REQUIRED,
                'The list of status checks to require in order to merge into this branch'
            )
            ->addOption(
                'strict',
                'S',
                InputOption::VALUE_NONE,
                'Require branches to be up to date before merging'
            )
            ->addOption(
                'enforce-admin',
                'a',
                InputOption::VALUE_NONE,
                'Enforce restriction to admins as well'
            )
            ->addOption(
                'pr-check',
                'p',
                InputOption::VALUE_NONE,
                'Require PR review'
            )
            ->addOption(
                'pr-team',
                't',
                InputOption::VALUE_IS_ARRAY + InputOption::VALUE_REQUIRED,
                'Teams allowed to bypass PR review'
            )
            ->addOption(
                'pr-user',
                'u',
                InputOption::VALUE_IS_ARRAY + InputOption::VALUE_REQUIRED,
                'Users allowed to bypass PR review'
            )
            ->addOption(
                'team',
                'T',
                InputOption::VALUE_IS_ARRAY + InputOption::VALUE_REQUIRED,
                'Teams allowed to push directly to branch'
            )
            ->addOption(
                'user',
                'U',
                InputOption::VALUE_IS_ARRAY + InputOption::VALUE_REQUIRED,
                'Users allowed to push directly to branch'
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

        /**
         * Construct settings
         * https://developer.github.com/v3/repos/branches/#update-branch-protection
         */

        $pr_check = null;
        $status_check = null;

        if ($input->getOption('pr-check')) {
            $pr_check = [
                'dismissal_restrictions' => [
                    'users' => $input->getOption('pr-user'),
                    'teams' => $input->getOption('pr-team')
                ],
                'dismiss_stale_reviews' => true,
                'require_code_owner_reviews' => true
            ];
        }

        if (!empty($input->getOption('status-check'))) {
            $status_check = [
                'strict' => $input->getOption('strict'),
                'contexts' => $input->getOption('status-check')
            ];
        }

        $params = [
          'required_status_checks' => $status_check,
            'required_pull_request_reviews' => $pr_check,
            'enforce_admins' => $input->getOption('enforce-admin'),
            'restrictions' => [
                'users' => $input->getOption('user'),
                'teams' => $input->getOption('team')
            ]
        ];

        return  self::$githubClient->api('repo')->protection()
            ->update(
                $input->getArgument('org'),
                $input->getArgument('repo'),
                $input->getArgument('branch'),
                $params
            );
    }
}
