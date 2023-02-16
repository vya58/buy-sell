<?php
$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

$config = [
  'id' => 'basic',
  'basePath' => dirname(__DIR__),
  'language' => 'ru-RU',
  'homeUrl' => '/',
  'basePath' => dirname(__DIR__),
  'bootstrap' => ['log'],
  'aliases' => [
    '@bower' => '@vendor/bower-asset',
    '@npm'   => '@vendor/npm-asset',
  ],
  'components' => [
    'request' => [
      // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
      'cookieValidationKey' => 'hOn7x3MB8QE0dRZ15f3W0ruDtTwoo9lh',
      'baseUrl' => '',
    ],
    'authClientCollection' => [
      'class' => 'yii\authclient\Collection',
      'clients' => [
        'vkontakte' => [
          'class' => 'yii\authclient\clients\VKontakte',
          'clientId' => $config['vk_id'],
          'clientSecret' => $config['vk_key'],
          'returnUrl' => 'http://buysell/login/auth?authclient=vkontakte',
          'scope' => 'email',
        ],
      ],
    ],
    'authManager' => [
      'class' => 'yii\rbac\DbManager',
      //'cache' => 'cache',
    ],
    'cache' => [
      'class' => 'yii\caching\FileCache',
    ],
    'user' => [
      'identityClass' => 'app\models\User',
      'enableAutoLogin' => true,
    ],
    'firebase' => [
      'class' => 'yii\components\Firebase',
    ],
    'errorHandler' => [
      'errorAction' => 'site/error',
    ],
    'mailer' => [
      'class' => \yii\symfonymailer\Mailer::class,
      'viewPath' => '@app/mail',
      // send all mails to a file by default.
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
    'urlManager' => [
      'enablePrettyUrl' => true,
      'showScriptName' => false,
      'enableStrictParsing' => false,
      'rules' => [
        'offers/<id:\d+>' => 'offers/index',
        'comments/<id:\d+>' => 'comments/index',
        '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
      ],
    ],
  ],
  'params' => $params,
];

if (YII_ENV_DEV) {
  // configuration adjustments for 'dev' environment
  $config['bootstrap'][] = 'debug';
  $config['modules']['debug'] = [
    'class' => 'yii\debug\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    'allowedIPs' => ['*'],
  ];

  $config['bootstrap'][] = 'gii';
  $config['modules']['gii'] = [
    'class' => 'yii\gii\Module',
    // uncomment the following to add your IP if you are not connecting from localhost.
    'allowedIPs' => ['*'],
  ];
}

return $config;
