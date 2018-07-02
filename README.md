# ZXVenturesCodeChallenge
Solution to [Back-end Challenge](https://github.com/ZXVentures/code-challenge/blob/master/backend.md) from [ZXVentures](https://github.com/ZXVentures)

## Dependencies
- [Docker](https://www.docker.com/)

## Installation
```
sudo docker-compose build && sudo docker-compose up -d
```
Two containers will be initialized:
- ZXVenturesCodeChallengeBackend-php-mongo (MongoDB)
- ZXVenturesCodeChallengeBackend-php-app (REST API connected to the host port 8000)

#### Database
Import data based in this [json file](https://raw.githubusercontent.com/ZXVentures/ze-code-challenges/master/files/pdvs.json):
```
sudo docker exec -it ZXVenturesCodeChallengeBackend-php-app sh -c "php ./bin/console DatabaseImportCommand"
```

### Tests
```
sudo docker exec -it ZXVenturesCodeChallengeBackend-php-app sh -c "php ./bin/composer.phar test"
```

## REST API
```
POST    /pdv
GET     /pdv/:pdv_id
GET     /pdv/covers?lng=:lng&lat=:lat
```
[Postman](https://www.getpostman.com/) collection with some examples: [download](https://raw.githubusercontent.com/enicioli/ZXVenturesCodeChallengeBackend-php/master/resources/ZXVenturesCodeChallengeBackend-php.postman_collection.json).

#### Main technologies
- [Docker](https://www.docker.com/)
- [PHP 7.1](http://php.net/)
- [MongoDB](https://www.mongodb.com/)
- [Supervisor](http://supervisord.org/)
- [Composer](https://getcomposer.org/)
- [Doctrine MongoDB ODM](https://www.doctrine-project.org/projects/mongodb-odm.html)
- [PHPUnit](https://phpunit.de/)
- [JSON Schema](http://json-schema.org/)
