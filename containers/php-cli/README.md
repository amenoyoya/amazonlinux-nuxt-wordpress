# docker: php command

## Environment

- OS: Ubuntu 20.04
- Docker: 19.03.12
    - docker-compose: 1.26.0

### Setup
```bash
# export current user id => service://cli:workuser.id
## measures for permission problems
$ export UID

# build docker containers
$ docker-compose build

# launch docker containers
## - service://cli <php:7.4-cli>
##   - tcp://localhost:<port> => service://cli:<port>
$ docker-compose up -d

# add execution permission to ./run
$ chmod +x ./run
```

***

## PHP development

### Laravel Framework
```bash
# composer global install: laravel/installer
# $ docker-compose exec cli composer global require laravel/installer
$ ./run composer global require laravel/installer

# create new laravel project: app
# $ docker-compose exec cli laravel new app
$ ./run laravel new app

# launch laravel development server
## - working dir (-w): host:./app/ => service://cli:/work/app/
# $ docker-compose exec -w /work/app/ cli php artisan serve
$ opt='-w /work/app/' ./run php artisan serve

# http://localhost:8000 => service://cli:8000
```
