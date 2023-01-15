<?php

/**
 * Для подключения Firebase JS SDK. Сделан отдельным классом, для установки <script type="module">
 *
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class FirebaseAsset extends AssetBundle
{
  public $basePath = '@webroot';
  public $baseUrl = '@web';
  public $css = [
    //'css/index.css',
  ];
  public $js = [
    //'https://www.gstatic.com/firebasejs/8.2.1/firebase-app.js',
    //'https://www.gstatic.com/firebasejs/8.2.1/firebase-database.js',
    'js/firebase.js'
  ];
  public $jsOptions = [
    'type' => 'module',
];
  public $depends = [
    'yii\web\YiiAsset',
    'yii\bootstrap5\BootstrapAsset'
  ];
}
