<?php

return [
    'mode' => 'development',
    'backend' => [
        'frontName' => 'admin785'
    ],
    'directories' => [
        'root' => __DIR__ . '/../..',
        'static' => 'static',
        'view' => 'view' ,
        'media' => 'media',
        'document_root_is_pub' => true
    ],
    'db' => [
        'table_prefix' => '',
        'connection' => [
            'default' => [
                'host' => '127.0.0.1',
                'dbname' => 'my_db',
                'username' => 'root',
                'password' => '1245',
                'model' => 'mariadb',
                'engine' => 'innodb',
                'active' => '1'
            ]
        ]
    ]
];