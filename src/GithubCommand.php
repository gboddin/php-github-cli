<?php

namespace Gbo\PhpGithubCli;

use Github\Client;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class  GithubCommand extends SymfonyCommand
{
    /**
     * @var string
     */
    private $token;

    /**
     * @var Client
     */
    static protected $githubClient;

    /**
     * @param string|null $token
     * @param string|null $name
     */
    public function __construct($token=null, $name = null)
    {
        if(is_null(self::$githubClient)) {
            self::$githubClient = new \Github\Client();
            if($token !== null) {
                self::$githubClient->authenticate($token,
                    null,
                    \Github\Client::AUTH_HTTP_TOKEN);
            }
        }
        parent::__construct($name);
    }
}
