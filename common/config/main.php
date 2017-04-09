<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        //>>rbac权限控制需要在common配置
        'authManager'=> [
            'class'=> \yii\rbac\DbManager::className(),
        ]
    ],
];
