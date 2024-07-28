#!/bin/bash

docker build -t richeenektar/tmplink:latest -f Dockerfile ./
docker push richeenektar/tmplink:latest
