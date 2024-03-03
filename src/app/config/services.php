<?php


use Phalcon\Mvc\View;
use Phalcon\Url;
use Phalcon\Db\Adapter\Pdo\Mysql;
use Phalcon\Config;
use Phalcon\Escaper;
use Phalcon\Session\Adapter\Stream as SessionAdapter;
use Phalcon\Session\Manager as SessionManager;
use Phalcon\Logger;
use Phalcon\Logger\Adapter\Stream;
use Phalcon\Http\Response\Cookies;
use Phalcon\Crypt;



$container->set(
    'cookies',
    function () {
        $cookies = new Cookies();
        $cookies->useEncryption(true);
        return $cookies;
    }
);


$container->set(
    "crypt",
    function () {
        $crypt = new Crypt();
        $crypt->setKey('AED@!sft56$'); // Use a unique Key!
        $key = "T4\xb1\x8d\xa9\x98\x054t7w!z%C*F-Jk\x98\x05\x5c";
        $crypt->setKey($key);
        return $crypt;
    }
);

$container->set(
    'logger',
    function () {

        $adapter1  = new Stream('../app/logs/login.log');
        $adapter2  = new Stream('../app/logs/signup.log');
        $logger  = new Logger(
            'messages',
            [
                'login' => $adapter1,
                'signup' => $adapter2,
            ]
        );
        return $logger;
    }
);

$container->setShared('session', function () {
    $session = new SessionManager();
    $files = new SessionAdapter([
        'savePath' => sys_get_temp_dir(),
    ]);
    $session->setAdapter($files);
    $session->start();

    return $session;
});

$container->setShared('db', function () {
    $database = $this->getConfig();
    return new Mysql(
        [
            'host'     => $database->database->host,
            'username' =>  $database->database->username,
            'password' => $database->database->password,
            'dbname'   => $database->database->dbname,
        ]
    );
});



$container->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . '/views/');
        return $view;
    }
);



$container->set(
    'url',
    function () {
        $url = new Url();
        $url->setBaseUri('/');
        return $url;
    }
);

$container->set(
    'config',
    function () {

        $fileName = "../app/config/config.php";
        // $factory= new ConfigFactory();
        // return $factory->newInstance("php",$fileName);
        $config = new Config([]);
        $array = new \Phalcon\Config\Adapter\Php($fileName);
        $config->merge($array);
        return $config;
    }
);




$container->set(
    'escaper',
    function () {
        return new Escaper();
    }
);
