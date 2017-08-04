#!/usr/bin/env bash

if ! docker ps -a --format={{.Names}} | grep mysql > /dev/null; then
    docker run --name mysql -v /mnt/mysql:/var/lib/mysql -e MYSQL_ROOT_PASSWORD=JV4yLWsPlzQkCvMz3E5j -d -p 3306:3306 mysql
elif ! docker ps --format={{.Names}} | grep mysql > /dev/null; then
    docker start mysql > /dev/null;
    echo "mysql has been started"
else
    echo "mysql is already running"
fi
if ! docker ps -a --format={{.Names}} | grep task > /dev/null; then
    docker build -t task .
    docker run --name task -v "$PWD":/var/www/html -d -p 80:80 --link mysql:mysql task
elif ! docker ps --format={{.Names}} | grep task > /dev/null; then
    docker start task > /dev/null;
    echo "task has been started"
else
    echo "task is already running"
fi
