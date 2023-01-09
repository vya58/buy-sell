1) Для установки базы данных выполните в SQL-менеджере код, в файле data/schema.sql

2) Для установки DbManager выполните в консоли, в папке проекта, команду: yii migrate --migrationPath=@yii/rbac/migrations

3) Для настройки ролей RBAC выполните в консоли, в папке проекта, команду: php yii my-rbac/init

4) Примените имеющиеся миграции: yii migrate

5) Для работы с Firebase Realtime Database установите библиотеку Firebase, выполнив в консоли, в папке проекта, команду: composer require kreait/firebase-php

6) Зарегистрируйтесь на сайте Firebase (https://console.firebase.google.com/) и создайте свой проект

7) Подключите к нему проект Buysell. При подключении я использовал Firebase SDK, скрипт, предложенный для подключения - в файле ./web/js/firebase.js за исключением const firebaseConfig = {...}

8) Параметры конфигурации подключения Firebase SDK (const firebaseConfig = {...}) записаны в скрипте ./web/js/firebaseConfig.js, добавленный в .gitignore, в формате:

const firebaseConfig = {
  apiKey: "ХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХ",
  authDomain: "идентификатор проекта.firebaseapp.com",
  databaseURL: "https://идентификатор проекта-default-rtdb.europe-west1.firebasedatabase.app",
  projectId: "идентификатор проекта",
  storageBucket: "идентификатор проекта.appspot.com",
  messagingSenderId: "000000000000",
  appId: "ХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХ"
};

Вы можете сделать также или оставить всё в одном файле firebase.js, скопировав туда весь предложенный на странице регистрации приложения (https://console.firebase.google.com/project/...) код
