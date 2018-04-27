<?php

namespace Gbo\PhpGithubCli;

use Github\Client;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class GithubCommand extends SymfonyCommand
{
    /**
     * @var Client
     */
    static protected $githubClient;

    /**
     * @param string|null $token
     * @param string|null $name
     */
    public function __construct($token = null, $name = null)
    {
        if (is_null(self::$githubClient)) {
            self::$githubClient = new \Github\Client();
            if ($token !== null) {
                self::$githubClient->authenticate(
                    $token,
                    null,
                    \Github\Client::AUTH_HTTP_TOKEN
                );
            }
        }
        parent::__construct($name);
    }

    /**
     * Default execute, this allow for parsing of github's API output
     * in a central place
     *
     * The real exec is therefore githubExec
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $githubOutput = $this->githubExec($input, $output);
        switch ($input->getOption('output-format')) {
            case 'json':
                $output->writeln(
                    json_encode(
                        $githubOutput,
                        JSON_PRETTY_PRINT
                    )
                );
                break;

            case 'csv':
                $this->csvOutput($output, $githubOutput);
                break;
            case 'human':
            default:
                $this->humanOutput($output, $githubOutput);
                break;
        }
    }

    /**
     * Add some default options and call githubConfigure()
     */
    protected function configure()
    {
        $this->addOption(
            'output-format',
            'of',
            InputOption::VALUE_REQUIRED,
            'Output format (human, json)',
            'human'
        );
        $this->githubConfigure();
    }

    /**
     * All GithubCommands must implement githubExec()
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return mixed
     */
    abstract protected function githubExec(InputInterface $input, OutputInterface $output);

    /**
     * @return mixed
     */
    abstract protected function githubConfigure();

    /**
     * Override to do something else than OK
     * @return mixed
     */
    protected function humanOutput(OutputInterface $output, $result)
    {
        if (!empty($result->message)) {
            //failed
            $output->writeln('<error>[FAILED]</error> '.$this->getName().' : '.$result->message);
        } else {
            $output->writeln('<info>[OK]</info> '.$this->getName());
        }
    }

    /**
     * Override to do something else than OK
     * @return mixed
     */
    protected function csvOutput(OutputInterface $output, $result)
    {
        throw new \Exception('Not implemented');
    }
}
