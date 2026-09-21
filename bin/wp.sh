#!/bin/zsh
# WP-CLI against the `philhoyt-new` Local site. Local's MySQL listens on a socket,
# not TCP, so point mysqli at it. Recreate the symlink if Local changes the
# site id: ln -sfn "~/Library/Application Support/Local/run/<id>/mysql/mysqld.sock" ~/.local-sockets/philhoyt-new.sock
php -d mysqli.default_socket="$HOME/.local-sockets/philhoyt-new.sock" -d error_reporting="E_ALL & ~E_DEPRECATED" "$(command -v wp)" --path="$HOME/Local Sites/philhoyt-new/app/public" "$@"
