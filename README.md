# Great Foods Backend Developer Test

This repository is a submission for a technical test, instructions excluded from private repository.

## Vanilla Execution

The project will work on Ubuntu 20.04 using PHP 8.0, the extensions mbstring and xml are required.

1. Enter the project root directory
2. Run `composer install`
3. Run `php vendor/bin/phpunit` to run all tests

## Vagrant Execution
If you have Vagrant / VirtualBox installed, you can set up this project using a basic Vagrantfile.

1. Enter the project root directory
2. Run `vagrant up`
3. Run `vagrant ssh`
4. Run `cd /data/websites/great-foods-api-handler`
5. Run `composer install`
6. Run `php vendor/bin/phpunit` to run all tests

# Project Notes

 - The use of the Guzzle ClientInterface would be better handled by using the PSR Client interface for more flexibility.
 - Models are set up as a set up read only format, whilst both models are the same, you'd imagine in reality they'd differ.
 - The models do not have any associations loaded in but this would be a good addition if it wasn't out of scope.
 - Service methods are simple but you would expect more flexibility with a real world application, i.e. `getMenuById()`