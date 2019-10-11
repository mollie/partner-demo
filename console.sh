#!/usr/bin/env sh

set -a

source .env

function help() { ### Show the list of possible functions
  grep -E '^function.*?###' $0 | sed "s/^function //g" | sed "s/()/:/g" | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-12s\033[0m %s\n", $1, $2}'
}

function up() { ### build and start development environment
  docker-compose -f docker-compose-dev.yml up --build
}

function down() { ### stop development environment
  docker-compose -f docker-compose-dev.yml down
}

function logs() { ### show php-fpm logs
  docker-compose -f docker-compose-prod.yml exec php-fpm logs -f
}

function bash() { ### access a given container
  docker-compose -f docker-compose-dev.yml exec "$1" bash
}

if [ "_$1" = "_" ]; then
  help
else
  "$@"
fi
