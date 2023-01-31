<?php
$config = parse_ini_file('/OpenServ/domains/config/buysell_config.ini', true);

return [
  'buysellEmail' => 'admin@buysell.com',
  //'adminEmail' => 'admin@example.com',
  //'senderEmail' => 'noreply@example.com',
  //'senderName' => 'Example.com mailer',

  // Количество выводимых карточек новых объявлений
  'newOffersCount' => 8,

  // Количество выводимых карточек самых обсуждаемых объявлений
  'mostTalkedOffersCount' => 8,

  // Количество выводимых карточек объявлений в категории
  'pageSize' => 8,

  // Максимальное количество в анонсе карточки объявления
  // Согласно ТЗ "Анонс, не более 55 символов." 52 - с учётом многоточия в конце оборванной строки
  'offerTextLength' => 52,

  // Максимальная длина имени при выведении в чате продавца
  'maxNameLength' => 24,

  'firebaseDatabaseUri' => $config['firebase_database_uri'],
  'firebaseServiceAccountShape' => $config['firebase_service_account_shape'],

  //Конфигурация транспорта Symfony Mailer 'MAILER_DSN' для достаки писем через SMTP:
  'mailerDsn' => $config['mailer_dsn'],

];
