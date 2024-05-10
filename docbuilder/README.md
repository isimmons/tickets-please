# Docs With Doctum

The same Doctum Laravel uses to generate the api docs for the framework.

I learned how to use it by looking at [github/laravel.com](https://github.com/laravel/laravel.com/tree/master)

I don't have versioning working yet but am generating working docs so YAY!.

Doctum has package version conflicts if you try to install it in the base project which is why it is in it's own
directory.

Steps to generate docs without using the .sh scripts Laravel is using.
First, my build dir is called docbuilder. You have to create a your build/doctum directory, then drop in the
composer.json and .gitignore files. Then the doctum.php file with your config setup.

1. `cd docbuilder/doctum`
2. `composer install`
3. `php ./vendor/bin/doctum.php update ./doctum.php -v --ignore-parse-errors`
4. `cp -R build/docs ../../public`
5. Create the following route in routes/web.php

```php
Route::get('/docs', function () {
    return File::get(public_path('docs/index.html'));
});
```
This is the basics. Should be good to visit `http://tickets-please.test/docs`
