<?php
use Phalcon\Loader;

$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
        APP_PATH . '/component/',
       
    ]
);
$loader->registerNamespaces(
    [
        'App\Handler' => APP_PATH . '/handler/'
        
    ]
);

$loader->register();