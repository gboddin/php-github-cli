# PHP github CLI tool


## Build status

|Service|master|develop|
|--|--|--|
|CI Status|![Status](https://hold-on.nobody.run/api/badges/gboddin/php-github-cli/status.svg?branch=master)|![Status](https://hold-on.nobody.run/api/badges/gboddin/php-github-cli/status.svg?branch=develop)|

## Intro

This tool is meant to be used in by human and automation tools with PHP projects.

## Install

```sh
composer install gboddin/php-github-cli
```

## Configuration

The tool uses Github tokens to authenticate. They can be set in 3 ways :

- Using the GITHUB_API_TOKEN environment variable
- Global config file ( ~/.gitconfig )
- Local config file ( ./.git/config )

The git config file should look as follow :

```ini
[github]
token=xxxxxxxxx

```

## Usage

```sh
ghcli pr:comment octocat Hello-World 1 "Hello"
ghcli status:set octocat Hello-World 33375d5f193cfa8d0fe2998ec312c871639257bf -s success -c my/context -t http://www.google.com -d "You have created/updated a commit status my friend"
ghcli deploy:create octocat Hello-World -r 33375d5f193cfa8d0fe2998ec312c871639257bf -e Staging -d "Super deploy"
ghcli deploy:list octocat Hello-World
ghcli deploy:update octocat Hello-World 23079543 -s error -d "Failed it !"
ghcli branch:create octocat Hello-World -t depedency/2.3.78 -s master
ghcli ref:create octocat HelloWorld -r refs/heads/depedency/2.3.78 -s bdb276f3227a19e826d8d511cfd53639153e0a6a
ghcli file:update octocat HelloWorld build.properties.lock 'Updated to version 2.3.78' 'depedency.version = 2.3.78' 'depedency/2.3.78' -c
ghcli pr:create octocat HelloWorld -t "Minor upgrade 2.3.78" -s depedency/2.3.78  -b master -d "Minor upgrade depedency/2.3.78"
ghcli branch:protect octocat HelloWorld master -T qa-support -t qa-support

```

## Current modules

```
ghcli version 0.2.8

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
  help            Displays help for a command
  list            Lists commands
 branch
  branch:create   Create a branch from another branch
  branch:protect  Protect a branch
 deploy
  deploy:create   Creates a deployment
  deploy:list     List deployments for a repo
  deploy:update   Creates a deployment status
 file
  file:update     Updates a file
 pr
  pr:comment      Comment a pull request
  pr:create       Create a pull request
 ref
  ref:create      Create a branch from another ref
 status
  status:set      Creates/updates a commit status
```
