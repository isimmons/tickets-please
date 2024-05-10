<?php

require __DIR__.'/vendor/autoload.php';

use Doctum\Doctum;
use Symfony\Component\Finder\Finder;
//use Doctum\Version\GitVersionCollection;
//use Doctum\RemoteRepository\GitHubRemoteRepository;

$iterator = Finder::create()
    ->files()
    ->name('*.php')
    ->exclude('stubs')
    ->in($dir = __DIR__.'/../../app');

//$versions = GitVersionCollection::create($dir)
//    ->add('6.x', 'Laravel 6.x')
//    ->add('7.x', 'Laravel 7.x')
//    ->add('8.x', 'Laravel 8.x')
//    ->add('9.x', 'Laravel 9.x')
//    ->add('10.x', 'Laravel 10.x')
//    ->add('11.x', 'Laravel 11.x')
//    ->add('master', 'Laravel Dev');

return new Doctum($iterator, [
    'title' => 'Tickets Please',
//    'versions' => $versions,
    'build_dir' => __DIR__.'/build/docs',
    'cache_dir' => __DIR__.'/cache/docs',
    'source_dir' => __DIR__."/../../app",
    'default_opened_level' => 2,
//    'remote_repository' => new GitHubRemoteRepository('laravel/framework', dirname($dir)),
    'base_url' => 'https://tickets-please.test/api/v1',
]);
