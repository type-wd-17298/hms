<?php

error_reporting(0);
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$db_project = require __DIR__ . '/db_project.php';
$db_myoffice = require __DIR__ . '/db_myoffice.php';
$db_payroll = require __DIR__ . '/db_payroll.php';
$db_hosxp = require __DIR__ . '/db_hosxp.php';
$db_inventory = require __DIR__ . '/db_inventory.php';
$db_servicedesk = require __DIR__ . '/db_servicedesk.php';
$db_erp = require __DIR__ . '/db_erp.php';
$config = [
    'id' => 'basic',
    'name' => 'HMS :: โรงพยาบาลสมเด็จพระสังฆราช องค์ที่ 17',
    'basePath' => dirname(__DIR__),
    //'bootstrap' => ['log'],
    'language' => 'th_TH',
    'timeZone' => 'Asia/Bangkok',
    'defaultRoute' => '/office/official/executive', # กำหนดหน้าหลัก
    'on beforeRequest' => function ($event) {
        if (!Yii::$app->request->isSecureConnection) {
            $url = Yii::$app->request->getAbsoluteUrl();
            $url = str_replace('http:', 'https:', $url);
            Yii::$app->getResponse()->redirect($url);
            Yii::$app->end();
        }
    },
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
    'modules' => [
        'servicedesk' => [
            'class' => 'app\modules\servicedesk\Module',
        ],
        'plan' => [
            'class' => 'app\modules\plan\Module',
        ],
        'kiosk' => [
            'class' => 'app\modules\kiosk\Module',
        ],
        'nurse' => [
            'class' => 'app\modules\nurse\Module',
        ],
        'inventory' => [
            'class' => 'app\modules\inventory\Module',
        ],
        'office' => [
            //Paperless Office
            'class' => 'app\modules\office\Module',
        ],
        'hr' => [
            'class' => 'app\modules\hr\Module',
        ],
        'project' => [
            'class' => 'app\modules\project\Module',
        ],
        'report' => [
            'class' => 'app\modules\report\Module',
        ],
        'survey' => [
            'class' => 'app\modules\survey\Module',
        ],
        'pdfjs' => [
            'class' => '\yii2assets\pdfjs\Module',
        ],
        'line' => [
            'class' => 'app\modules\line\Module',
        ],
        'gridview' => [
            'class' => '\kartik\grid\Module',
        ],
        'user' => [
            'class' => 'dektrium\user\Module',
            'enableUnconfirmedLogin' => true,
            'confirmWithin' => 21600,
            'cost' => 12,
            'admins' => ['ptaung'],
            //
            'modelMap' => [
                'RegistrationForm' => 'app\models\ExtRegistrationForm',
                'Profile' => 'app\models\ExtProfile',
                'UserSearch' => 'app\models\ExtUserSearch',
            ],
            'controllerMap' => [
                'admin' => 'app\controllers\ExtadminController',
                'security' => [
                    'class' => 'app\controllers\ExtsecurityController',
                    'layout' => '@app/themes/custom/layouts/main_login',
                    'on ' . dektrium\user\controllers\SecurityController::EVENT_AFTER_AUTHENTICATE => function ($e) {
                        If (isset(Yii::$app->user->identity->profile)) {
                            @\app\components\Cdata::getDataUserOnline();
                            if (strlen(Yii::$app->user->identity->profile->cid) <> 13 || Yii::$app->user->identity->profile->name == '' || Yii::$app->user->identity->profile->lname == '') {
                                Yii::$app->response->redirect(['/user/settings/profile']);
                                Yii::$app->end();
                            }
                        }
                    },
                    'on ' . dektrium\user\controllers\SecurityController::EVENT_AFTER_LOGIN => function ($e) {
                        @\app\components\Cdata::getDataUserOnline();
                        if (strlen(Yii::$app->user->identity->profile->cid) <> 13 || Yii::$app->user->identity->profile->name == '' || Yii::$app->user->identity->profile->lname == '') {
                            Yii::$app->response->redirect(['/user/settings/profile']);
                            Yii::$app->end();
                        }
                    },
                ],
                'recovery' => [
                    'class' => 'dektrium\user\controllers\RecoveryController',
                    'layout' => '@app/themes/custom/layouts/main_login',
                ],
                'registration' => [
                    'class' => 'dektrium\user\controllers\RegistrationController',
                    'layout' => '@app/themes/custom/layouts/main_login',
                    'on ' . dektrium\user\controllers\RegistrationController::EVENT_AFTER_REGISTER => function ($e) {
                        Yii::$app->response->redirect(['/user/login'])->send();
                        \app\modules\line\components\lineBot::send('มีการลงทะเบียนเข้าสู่ระบบ ไม่ผ่าน LINE-APP', ['HiCxe8Lrjweo28sY0egmTogVWzhZcgQThB4trgZv9Fm']); //แจ้ง Admin ตอนมีการลงทะเบียน
                        Yii::$app->end();
                    },
                ],
            ],
        ],
        'admin' => [
            'class' => 'mdm\admin\Module',
            'layout' => 'left-menu',
            'mainLayout' => '@app/themes/custom/layouts/main.php',
        ]
    ],
    'components' => [
        'image' => [
            'class' => 'yii\image\ImageDriver',
            'driver' => 'GD', //GD or Imagick
        ],
        'session' => [
            'name' => 'PHPMISSESSID_EPAYSLIP', //id app session
            'class' => 'yii\web\DbSession',
            'db' => 'db',
            'sessionTable' => 'session',
            'timeout' => 1440 * 60,
            'writeCallback' => function ($session) {
                return [
            'user_id' => Yii::$app->user->id,
            'last_write' => date('Y-m-d H:i:s'),
                ];
            },
        ],
        'authClientCollection' => [
            'class' => yii\authclient\Collection::className(),
            'clients' => [
                'line' => [
                    'class' => 'app\auth\LineOAuth',
                    'clientId' => '1657526178', //clientId ที่กำหนดให้
                    'clientSecret' => 'd31cd2e0a2e66b5e8ab8f469ac9e6e7a', //clientSecret ที่กำหนดให้
                ],
				 'providerid' => [
                    'class' => 'app\auth\HealthIDOAuth',
                    'clientId' => '01948896-39f3-7b52-8ee8-2ea02a6709a5', //clientId ที่กำหนดให้
                    'clientSecret' => 'c1662a39c749f094335beb6230eb9a830895d04f', //clientSecret ที่กำหนดให้
                ],
				
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\bootstrap\BootstrapPluginAsset' => false,
                'yii\bootstrap\BootstrapAsset' => false,
                /*
                  'dosamigos\google\maps\MapAsset' => [
                  'options' => [
                  'key' => $params['googleMapToken'],
                  'language' => 'th',
                  //'version' => '3.1.18'
                  ]
                  ],
                 *
                 */
                'dosamigos\google\maps\MapAsset' => false,
//                'kartik\form\ActiveFormAsset' => [
//                    'bsDependencyEnabled' => false // do not load bootstrap assets for a specific asset bundle
//                ],
            ],
        ],
        'view' => [
            'theme' => [
                #'basePath' => '@app/themes/sbclean',
                #'baseUrl' => '@web/themes/sbclean',
                /*
                  'pathMap' => [
                  '@app/views' => '@app/themes/custom',
                  '@dektrium/user/views' => '@app/themes/custom/user'
                  ],
                 *
                 */
                'pathMap' => [
                    '@app/views' => '@app/themes/custom', //metronic  //custom  //vuexy
                    '@dektrium/user/views' => '@app/themes/custom/user',
                // '@app/modules/views' => '@app/themes/vuexy',
                ],
            ],
        ],
        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'dateFormat' => 'php:j M Y',
            'datetimeFormat' => 'php:j M Y H:i',
            'timeFormat' => 'php:H:i',
            'timeZone' => 'UTC',
            'locale' => 'th-TH',
            'nullDisplay' => '-',
        ],
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'hgnjNJDs5rJMJFxoOCJSLQCm9wInsi0t123wqeqwsxa128eryidlHfds',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'dektrium\user\models\User',
            'enableAutoLogin' => false,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'db_project' => $db_project,
        'db_myoffice' => $db_myoffice,
        'db_payroll' => $db_payroll,
        'db_inventory' => $db_inventory,
        'db_servicedesk' => $db_servicedesk,
        'db_hosxp' => $db_hosxp,
		'db_erp' => $db_erp,
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => true,
            'rules' => [
            ],
        ],
    ],
    'as access' => [
        'class' => 'mdm\admin\components\AccessControl',
        'allowActions' => [
            'site/*',
            'user/security/*',
            'user/logout',
            'user/recovery/*',
            'user/registration/*',
            'user/settings/*',
            'line/default/callback',
            'survay/default/protect',
            'kiosk/*'
        ]
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
// configuration adjustments for 'dev' environment
    //$config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
            // uncomment the following to add your IP if you are not connecting from localhost.
            //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
