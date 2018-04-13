<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',

/*            'dsn' => 'mysql:host=138.201.117.184:6603;dbname=test',
            'username' => 'developer',
            'password' => 'EDFP7iBWvyjR3',*/

            'masterConfig' => [
                'username' => 'user',
                'password' => '128256asdf',
                'attributes' => [
                    // use a smaller connection timeout
                    PDO::ATTR_TIMEOUT => 10,
                ],
            ],

            // list of master configurations
            'masters' => [
                ['dsn' => 'mysql:host=10.12.25.24;port=3306;dbname=test'],
                ['dsn' => 'mysql:host=10.12.25.44;port=3306;dbname=test'],
                ['dsn' => 'mysql:host=10.12.25.45;port=3306;dbname=test'],
            ],



            /**
             * Андрей Маковский, [06.04.18 12:22]
            10.12.25.24
            10.12.25.44
            10.12.25.45

            Андрей Маковский, [06.04.18 12:26]
            user 128256asdf
             */



            'charset' => 'utf8',
            'tablePrefix'         => '',
            'enableSchemaCache'   => true,
            'schemaCacheDuration' => 360,
        ],
        /**
         *
            ip 10.12.25.30
            port master 3306
            port slave1 3307
            port slave2 3308
            user: user
            pass: vel3hiVeiRai
         *
         *
         *   'dsn' => 'mysql:host=10.12.25.30;port=3306;dbname=test',
        'username' => 'user',
        'password' => 'vel3hiVeiRai',

        'slaveConfig' => [
        'username' => 'user',
        'password' => 'vel3hiVeiRai',
        'attributes' => [
        // используем небольшой таймаут для соединения
        PDO::ATTR_TIMEOUT => 1,
        ],
        ],

        'slaves' => [
        ['dsn' => 'mysql:host=10.12.25.30;port=3307;dbname=test'],
        ['dsn' => 'mysql:host=10.12.25.30;port=3308;dbname=test'],
        ],
         *
         */

        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
