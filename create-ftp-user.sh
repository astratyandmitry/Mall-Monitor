#!/bin/bash

if [ -z "$2" ]; then
    echo "Please, provide login and password."
    exit 1
fi

echo "Creating new FTP-user:"
echo "- login: ftp-$1"
echo "- password: $2"

read -p "Do you confirm information? (Y/n) " -n 1 -r
echo # (optional) move to a new line
if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo "Okey, try one more time."
    exit 1
else
    USER="ftp-$1"
    PASS="$2"

    # shellcheck disable=SC2046
    sudo useradd -p $(openssl passwd -1 $PASS) $USER

    sudo mkdir /var/www/mallmonitor/storage/import/$USER
    sudo chown nobody:nogroup /var/www/mallmonitor/storage/import/$USER
    sudo chmod a-w /var/www/mallmonitor/storage/import/$USER
    sudo mkdir /var/www/mallmonitor/storage/import/$USER/files
    sudo chown $USER:$USER /var/www/mallmonitor/storage/import/$USER/files

    echo "User created."
fi
