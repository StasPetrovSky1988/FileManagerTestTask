<?php

if (YII_DEBUG) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=mysql;dbname=test_app_db',
        'username' => 'root',
        'password' => 'root',
        'charset' => 'utf8',

        // Schema cache options (for production environment)
        //'enableSchemaCache' => true,
        //'schemaCacheDuration' => 60,
        //'schemaCache' => 'cache',
    ];
} else {
    //Production
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'mysql:host=localhost;dbname=s58191_filemanager',
        'username' => 's58191_fm_user',
        'password' => 'NauG@:~_%i~iz#@x',
        'charset' => 'utf8',
    ];
}
