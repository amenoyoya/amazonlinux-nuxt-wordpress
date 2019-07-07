#!/bin/sh
expect -c "
set timeout 1
spawn yarn create nuxt-app app
expect -re '.*Project name.*'
send \n
expect -re '.*Project description.*'
send \n
expect -re '.*Use a custom server.*'
send \n
expect -re '.*Choose features.*'
send \n
expect -re '.*Use a custom UI.*'
send \n
expect -re '.*Use a custom test.*'
send \n
expect -re '.*Choose rendering.*'
send \n
expect -re '.*Author name.*'
send \n
expect -re '.*Choose a package.*'
send \n
interact
"
