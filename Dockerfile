FROM fpfis/httpd-php:7.1
ADD ghcli.phar /usr/bin/ghcli
ENTRYPOINT [ "/usr/bin/ghcli" ]
