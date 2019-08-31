FROM ruby:2.4

ENV APP_ROOT /var/www/app
WORKDIR $APP_ROOT

RUN apt-get update -qq && \
    apt-get install -y build-essential libpq-dev nodejs && \
    rm -rf /var/lib/apt/lists/* && \
    gem install bundler

COPY ./app/Gemfile $APP_ROOT/Gemfile
COPY ./app/Gemfile.lock $APP_ROOT/Gemfile.lock

RUN bundle install

EXPOSE  3000
