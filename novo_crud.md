# Criando novo crud

* Criando migration

    php artisan make:migration create_users_table

    local: database/migrations
    $table->text('description');
    $table->string('name', 50)->nullable()->change();
    $table->string('email')->unique();
    $table->timestamp('added_on', 0);
    $table->foreignId('setor_id')->nullable()->constrained('setores');
    $table->integer('votes');

* Criando model

    php artisan make:model Flight

    php artisan make:model Flight --migration

    app/Models

* Criando seed

    php artisan make:seeder UserSeeder

    php artisan db:seed --class=UserSeeder

    php artisan migrate:refresh


* Criando Controller

    app\Http\Controllers
    php artisan make:controller PhotoController --resource

* Criando rota

    routes/web.php


php artisan optimize:clear