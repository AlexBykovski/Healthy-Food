#!/usr/bin/env sh

export SYMFONY__TEST_DATABASE_USER=root
export SYMFONY__TEST_DATABASE_PASSWORD=root
export SYMFONY__TEST_DATABASE_NAME=healthy_food_test

# Setup DB and clear cache
php ./bin/console --env=test doctrine:database:drop --force
php ./bin/console --env=test doctrine:database:create
php ./bin/console --env=test doctrine:migrations:migrate --no-interaction
#php ./bin/console --env=test hautelook_alice:doctrine:fixtures:load --append --no-interaction
php ./bin/console --env=test cache:clear

./vendor/bin/phpunit

#php ./bin/console --env=test doctrine:database:drop --force
#php ./bin/console --env=test doctrine:database:create
#php ./bin/console --env=test doctrine:migrations:migrate --no-interaction
#php ./bin/console --env=test hautelook_alice:doctrine:fixtures:load --append --no-interaction
#php ./bin/console --env=test cache:clear

#./vendor/bin/behat