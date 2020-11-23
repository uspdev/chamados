#! /bin/bash

touch ./test.db
DB_CONNECTION=sqlite DB_DATABASE=./test.db php artisan migrate:fresh --seed
