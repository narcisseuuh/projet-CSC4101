#!/bin/sh

# dropping old database
symfony console doctrine:database:drop --force

# creating new database
symfony console doctrine:database:create

# creating new schema
symfony console doctrine:schema:create

# loading fixtures
echo "yes" | symfony console doctrine:fixtures:load

# starting server
symfony server:start