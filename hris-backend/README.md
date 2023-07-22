# Hris Backend

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

### CLI Commands

-   `php artisan make:model -m` // make model
-   `php artisan storage:link` // create symbolic link

### Jetstream Commands

-   `composer require laravel/jetstream`
-   `php artisan jetstream:install livewire`

### Then publish the jetstream views blade components

// in laravel 10 it's not important because it's already published

`php artisan vendor:publish --tag=jetstream-views`

// customize the SVG's located in the

-   `resources/views/vendor/jetstream/components/application-logo.blade.php`
-   `resources/views/vendor/jetstream/components/authentication-card-logo.blade.php`
    `resources/views/vendor/jetstream/components/application-mark.blade.php`

// enable disable feature jetstream

-   `config/fortify.php` => `features`
-   `config/jetstream.php` => `features`

## Relationships

-   `hasOne` => Describe that a model has one other model.
-   `hasMany` => Describe that a model has many other models.
-   `belongsTo` => Describe that a model belongs to another model, but in a one-to-many relationship.
-   `belongsToMany` => Describe that a model belongs to another model, but in a many-to-many relationship.

-   `Model Company`
    if to relationship become company_id (Laravel Convention)
    so you can use `belongsTo` or `hasOne` or `hasMany` but if you want to change
    the convention you can use `belongsTo` or `hasOne` or `hasMany` but you must
    define the `foreign key` and the `local key`

## Add response formatter to app/Helpers/ResponseFormatter.php

## Seeder and Factory

1. Create a seeder class
   `php artisan make:seeder NameSeeder`

2. Create a factory class
   `php artisan make:factory NameFactory`

3. Register the seeder / factory class in the DatabaseSeeder class
   `database/seeders/DatabaseSeeder.php`

4. Run the seeder
   `php artisan db:seed` / `php artisan db:seed --class=NameSeeder`

## Controller API

1. Create a controller class
   `php artisan make:controller NameController` / `php artisan make:controller NameController --resource`

## Request

1. Create a request class
   `php artisan make:request NameRequest`
