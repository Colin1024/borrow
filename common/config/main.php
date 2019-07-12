<?php
return [
    'timeZone' =>'Asia/Shanghai',
    'language'=>'zh-CN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=h5_hicolin_cn',
            'username' => 'h5_hicolin_cn',
            'password' => 'nxyD4m2enQzSPYfw',
            'charset' => 'utf8',
            'tablePrefix' => 'admin_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    
];
