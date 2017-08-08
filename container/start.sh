#!/usr/bin/env bash

if ! docker inspect --type=image myphp | grep myphp > /dev/null; then
    docker build -t myphp .
fi

docker-compose up -d
