<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Loader;
use Phalcon\Events\Manager as EventsManager;




define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

$container = new FactoryDefault();


include APP_PATH . '/config/services.php';
include APP_PATH . '/config/loader.php';

$loader = new Loader();
$loader->registerNamespaces(
    [
        'App\Handler' => APP_PATH . '/handler/'
        
    ]
);

$loader->register();


$application=new Application($container);
$eventsManager=new EventsManager();
$eventsManager->attach(
    'application:beforeHandleRequest',
    new App\Handler\NotificationListener()
);

$container->set(
    'EventsManager',
    $eventsManager
);

$application->setEventsManager($eventsManager);
try {
    // Handle the request
    $response = $application->handle(
        $_SERVER["REQUEST_URI"]
    );

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}