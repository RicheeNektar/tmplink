#!/bin/bash

docker build -t richeenektar/tmplink:latest -f Dockerfile ./

if [[ $1 == "prod" ]]
then
  docker push richeenektar/tmplink:latest
fi
