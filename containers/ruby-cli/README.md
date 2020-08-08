# docker: ruby command

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
## - service://cli <ruby:2.7>
##   - tcp://localhost:<port> => service://cli:<port>
$ docker-compose up -d

# add execution permission to ./run
$ chmod +x ./run
```

***

## Ruby on Rails

### Setup
```bash
# create new rails project: app
# $ docker-compose exec cli rails new app
$ ./run rails new app

# bundle install from ./app/Gemfile
## - working dir (-w): host:./app/ => service://cli:/work/app/
# $ docker-compose exec -w /work/app/ cli bundle install
$ opt='-w /work/app' ./run bundle install

# execute rails server
## - rails server: service://cli:0.0.0.0:3000
## - working dir (-w): host:./app/ => service://cli:/work/app/
# $ docker-compose exec -w /work/app/ cli rails s -p 3000 -b 0.0.0.0
$ opt='-w /work/app/' ./run rails s -p 3000 -b 0.0.0.0

# http://localhost:3000 => service://cli:3000
```
