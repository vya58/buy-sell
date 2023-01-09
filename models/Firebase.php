<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Kreait\Firebase\Factory;

/**
 *
 */
class Firebase extends Model
{
  //public $config = parse_ini_file('/OpenServ/domains/config/buysell_config.ini', true);
  public $factory = (new Factory)
    ->withServiceAccount('/OpenServ/domains/config/buysellchat-c6e28-firebase-adminsdk-4k4m2-1c314d0e34.json')
    ->withDatabaseUri($params['firebase_database_uri']);
  public $database = $factory->createDatabase();
  /*
  public function __construct(
    $config = parse_ini_file('/OpenServ/domains/config/buysell_config.ini', true),
    $factory = (new Factory)
      ->withServiceAccount('/OpenServ/domains/config/buysellchat-c6e28-firebase-adminsdk-4k4m2-1c314d0e34.json')
      ->withDatabaseUri($config['firebase_database_uri']),

  ) {
  }
  */
  /*
  $factory = (new Factory)
    ->withServiceAccount('/OpenServ/domains/config/buysellchat-c6e28-firebase-adminsdk-4k4m2-1c314d0e34.json')
    ->withDatabaseUri($config['firebase_database_uri']);

    $database = $factory->createDatabase();
    */
}
