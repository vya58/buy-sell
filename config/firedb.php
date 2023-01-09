<?php

require __DIR__ . '/../vendor/autoload.php';

$config = parse_ini_file('/OpenServ/domains/config/buysell_config.ini', true);

use Kreait\Firebase\Factory;

$factory = (new Factory)
  ->withServiceAccount('/OpenServ/domains/config/buysellchat-c6e28-firebase-adminsdk-4k4m2-1c314d0e34.json')
  ->withDatabaseUri($config['firebase_database_uri']);

  $database = $factory->createDatabase();
