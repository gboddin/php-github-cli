# PHP github CLI tool

## Intro

This tool is meant to be used in by human and automation tools with PHP projects.

## Install

```sh
composer install gboddin/php-github-cli
```

## Configuration

The tool uses Github tokens to authenticate. They can be set in 3 ways :

- Using the GITHUB_API_TOKEN environment variable
- Global config file ( ~/.git/config )
- Local config file ( ./.git/config )

The git config file should look as follow :

```ini
[github]
token=xxxxxxxxx

```

## Usage

```sh
./vendor/bin/ghcli pr:comment octocat Hello-World 1 "Hello"
./vendor/bin/ghcli status:set octocat Hello-World 33375d5f193cfa8d0fe2998ec312c871639257bf -s success -c my/context -t http://www.google.com -d "You have created/updated a commit status my friend"
```

## Current modules

```
ghcli alpha

Usage:
  command [options] [arguments]

Options:
  -h, --help            Display this help message
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi            Force ANSI output
      --no-ansi         Disable ANSI output
  -n, --no-interaction  Do not ask any interactive question
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug

Available commands:
  help        Displays help for a command
  list        Lists commands
 pr
  pr:comment  Comment a pull request
 status
  status:set  Creates/updates a commit status
```