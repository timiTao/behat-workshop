# behat-workshop
Simple material for workshop with behat and PHP 7.1.3.

Configure WWW server to have domain ``workshop.timitao`` on ``public`` folder.

# Run 

* ``composer install``
* ``php vendor/bin/behat`` - check if hello word test is correct and correct domain.

# Snippets


### Run PHP

* run php - `docker-compose run --rm php php COMMAND`
* via file
** create (on OSX) `/usr/local/bin/docker-php` and link to global:

    \#!/usr/bin/env bash
    
    \# echo "Current working directory: '"$(pwd)"'"
    
    cd $(pwd) && docker-compose run --rm php php  "$@"
	
	
Then  simply run `docker-php COMMAND` 

