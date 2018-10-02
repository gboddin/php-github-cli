FROM fpfis/httpd-php
ADD ghcli.phar /usr/bin/ghcli
ENTRYPOINT [ "/usr/bin/ghcli" ]
