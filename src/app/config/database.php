<?php
return new \Phalcon\Config([
    'database' =>[
        'adapter'     => 'Mysql',
        'host'        => 'mysql-server',
        'username'    => 'root',
        'password'    => 'secret',
        'dbname'      => 'db_cart',
        'charset'     => 'utf8',
    ],
   
]);
$aware = new Aware();