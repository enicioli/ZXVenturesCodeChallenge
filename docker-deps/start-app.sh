#!/bin/bash

if [ -z ${ENVIRONMENT} ]; then echo "ENVIRONMENT variable must be set"; exit; fi

cp ${APP_PATH}/resources/config/config.yml.$ENVIRONMENT ${APP_PATH}/resources/config/config.yml

/usr/bin/env php ./bin/console DatabaseImportCommand

supervisord
