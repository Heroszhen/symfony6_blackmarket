#! /bin/bash
#clear caches

php bin/console cache:clear
php bin/console cache:clear --env=prod
php bin/console cache:clear --env=dev