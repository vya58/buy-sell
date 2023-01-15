<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Kreait\Firebase\Factory;

/**
 *
 */
class FireDatabase extends Model
{
  /**
  * Инициализация компонента Firebase Realtime Database
  */
  public static function connect()
    {
      $factory = (new Factory)
      ->withServiceAccount( Yii::$app->params['firebase_service_account_shape'] . 'firebase-adminsdk-4k4m2-1c314d0e34.json')
      ->withDatabaseUri(Yii::$app->params['firebase_database_uri']);

        return $factory->createDatabase();
    }
}
