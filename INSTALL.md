1) Для установки базы данных выполните в SQL-менеджере код, в файле data/schema.sql

2) Для установки DbManager выполните в консоли, в папке проекта, команду: yii migrate --migrationPath=@yii/rbac/migrations

3) Для настройки ролей RBAC выполните в консоли, в папке проекта, команду: php yii my-rbac/init

4) Примените имеющиеся миграции: yii migrate

5) Для работы с Firebase Realtime Database установите библиотеку Firebase, выполнив в консоли, в папке проекта, команду: composer require kreait/firebase-php

6) Зарегистрируйтесь на сайте Firebase (https://console.firebase.google.com/) и создайте свой проект

7) Подключите к нему проект Buysell.

8) При подключении Firebase Realtime Database к проекту я использовал Firebase SDK для PHP (https://firebase-php.readthedocs.io/en/stable/realtime-database.html#) и Firebase SDK для JS (https://firebase.google.com/docs/database/web/start?authuser=0&hl=ru).
В идеале, нужно использовать что-то одно, т.к. они дублируют друг-друга. Но в соответствии с требованиями ТЗ проекта:
"<...>
— Чат работает в режиме “in realtime” (т.е. без перезагрузки страницы)
— Необходимо использовать firebase realtime db и его sdk для php: https://firebase-php.readthedocs.io/en/stable/realtime-database.html
— Со стороны php необходимо будет написать код, который подключается к этой БД и отправляет юзеру email уведомление, если у него есть непрочитанное сообщение в чате". Таким образом использование SDK PHP обязательно, но в тоже время работы в режиме “in realtime” с помощью PHP мне не удалось, т.к. "API базы данных реального времени не поддерживает прослушиватели событий в реальном времени". Использовался Pjax Yii2, отправка без перезагрузки идёт, а вот получение - нет.
Если использвать подключение без SDK JS, то оберните всю форму чата в offers/index.php (сразу после section.chat visually-hidden) в виджет Pjax. Если с SDK JS, то только поле ввода textarea.chat__form-message с кнопкой button.chat__form-button

9) Инициализация Firebase Realtime Database при использовании Firebase SDK для PHP происходит в конструкторе ChatFirebase.php:

$this->database = (new Kreait\Firebase\Factory)
      ->withServiceAccount(Yii::$app->params['firebaseServiceAccountShape'] . 'firebase-adminsdk-4k4m2-1c314d0e34.json')
      ->withDatabaseUri(Yii::$app->params['firebaseDatabaseUri'])->createDatabase();

Где параметр withServiceAccount() - json-файл закрытого ключа для Вашей учетной записи проекта в Firebase
Вы должны хранить файл JSON за пределами вашего репозитория кода, чтобы избежать случайного раскрытия его внешнему миру. Поэтому он вынесен за пределы проекта и подключён через Yii::$app->params['firebaseServiceAccountShape'], где Yii::$app->params['firebaseServiceAccountShape'] . 'firebase-adminsdk-4k4m2-1c314d0e34.json' составляет полное наименование вайла от корневого пути.

Параметр withDatabaseUri() - URI базы данных реального времени тоже подключён через Yii::$app->params['firebaseDatabaseUri']
Вы можете найти URI для вашей базы данных реального времени по адресу https://console.firebase.google.com/project/_/database . При входе в свой проект он будет представлен в виде:
https://<идентификатор проекта>-default-rtdb.<выбранная локация базы данных>.firebasedatabase.app/

10) Скрипт, предложенный для подключения и для работы с Firebase Realtime Database через JS находится в файле ./web/js/firebase.js за исключением Параметры конфигурации подключения Firebase SDK JS (const firebaseConfig = {...}).

9) Параметры конфигурации подключения Firebase SDK JS (const firebaseConfig = {...}) записаны в скрипте ./web/js/firebaseConfig.js, добавленный в .gitignore, в формате:

const firebaseConfig = {
  apiKey: "ХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХ",
  authDomain: "<идентификатор проекта>.firebaseapp.com",
  databaseURL: "https://<идентификатор проекта>-default-rtdb.<выбранная локация базы данных>.firebasedatabase.app",
  projectId: "<идентификатор проекта>",
  storageBucket: "<идентификатор проекта>.appspot.com",
  messagingSenderId: "000000000000",
  appId: "ХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХХ"
};

Вы можете сделать также или оставить всё в одном файле firebase.js, скопировав туда весь предложенный на странице регистрации приложения (https://console.firebase.google.com/project/...) код
