<?php

return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=192.168.200.9;dbname=hms_inventory_db',
    'username' => 'ptaung',
    'password' => 'QW2267er##',
    'charset' => 'utf8',
    // Schema cache options (for production environment)
    'enableSchemaCache' => true,
    'schemaCacheDuration' => 60,
    'schemaCache' => 'cache',
    'tablePrefix' => 'app_',
];
