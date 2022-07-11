#!/bin/sh

php -r 'file_put_contents("composer.json", str_replace("\"lint-php-cs", "\"deactivate-lint-php-cs-fix", file_get_contents("composer.json")));'
php -r 'file_put_contents("composer.json", str_replace("\"lint-rector", "\"deactivate-lint-rector", file_get_contents("composer.json")));'
