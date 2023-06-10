## **О проекте**

«Куплю. Продам» — интернет-сервис, упрощающий продажу или покупку любых вещей. Всё, что требуется для покупки: найти подходящее объявление и связаться с продавцом по email. Продать ненужные вещи ничуть не сложней: зарегистрируйтесь и заполните форму нового объявления.

## Техническое задание
https://htmlacademy.notion.site/6baff187cf034746bdd43891cef8c307

## **Техническое описание**

Для разработки сайта предлагается уже готовая верстка, от программиста требуется лишь написать бэкенд сайта, то есть сделать сайт динамическим, реализовать возможности по добавлению, просмотру заданий и приему откликов.

Разработка бэкенда должна вестись на языке программирования PHP 8, база данных — MySQL 8 и выше.

Не предполагается использование языка JavaScript для клиентского программирования — все необходимые скрипты уже есть в комплекте с вёрсткой.

При разработке схемы базы данных необходимо принимать во внимание какие сущности необходимо хранить, их поля и возможные связи друг с другом.

## **Технические требования по реализации**

Этот документ описывает не только поведение и все функции сайта, но также регламентирует технические детали реализации на уровне кода: используемые возможности фреймворка, необходимые библиотеки и так далее.

### **Общее**

Разработка всего проекта ведётся исключительно на основе фреймворка Yii 2 и его стандартных компонент. Если что-либо (валидация, пагинация, шаблонизация и тому подобное возможно сделать средствами компонент фреймворка, то это **должно быть** сделано этими средствами.

### **Формы**

Любые формы создаются только через компонент ActiveForm. Валидация любой формы выполняется стандартными средствами модели ActiveRecord. По максимуму используются встроенные валидаторы, а для особенных случаев реализуется свой валидатор (standalone либо в виде метода модели).

### **Авторизация**

Все процессы, которые обновляют задания, добавляют отклики и оценки должны выполняться только через компоненты авторизации. Для базового контроля доступа используются ACF-фильтры, для контроля доступа на основе ролей необходимо взять компонент RBAC. Такая авторизация должна проверять, что у пользователя есть право редактировать данный ресурс. Например, закрыть объявление может только пользователь, который его разместил или модератор. 

---

## **Описание функциональности**

### **Страницы приложения**

Веб-сайт «Куплю. Продам» состоит из нескольких страниц. Страницы разделяются на **публичные** (доступные всем) и **приватные** (доступные только авторизованным пользователям). Перечень всех страниц:

1. Главная страница (`/`). Доступна всем;
2. Регистрация (`/register`). Доступна всем;
3. Вход (`/login`). Доступна всем;
4. Объявление о продаже или покупки (`/offers/:id`). Доступна всем;
5. Объявления категории (`/offers/category/:id`). Доступна всем;
6. Результаты поиска (`/search`). Доступна всем;
7. Новое объявление (`/offers/add`). Только авторизованные пользователи;
8. Редактирование объявления (`/offers/edit/:id`). Только авторизованные пользователи;
9. Публикации (`/my`). Только авторизованные пользователи;
10. Комментарии (`/my/comments`). Только авторизованные пользователи.

Если неавторизованный пользователь (гость) обращается к странице, недоступной для гостей, то выполняется редирект (перенаправление) на страницу «Вход».

### **Главная страница**

1. Страница доступна гостям и авторизованным пользователям;
2. Для гостей в шапке отображается ссылка на страницу «Вход и регистрация» (`/login`);
3. Для авторизованных пользователей в шапке отображаются:
    
    — ссылки на страницы «Публикации» и «Комментарии»;
    
    — аватар авторизованного пользователя.
    
4. На странице отображаются два блока объявлений:
    
    — «Самое свежее». Максимум 8 объявлений отсортированных по дате создания. Сначала отображаются новые;
    
    — «Самые обсуждаемые». Максимум 8 объявлений. Под обсуждаемым объявлением подразумеваются объявления, у которых есть хотя бы один комментарий. Сначала отображаются самые комментируемые объявления;
    
5. Если объявлений с комментариями нет, то блок «Самые обсуждаемые» не выводится;
6. Если созданных объявлений нет, то на главной странице отображается текст «На сайте еще не опубликовано ни одного объявления.», а также кнопка «Вход и регистрация».
7. **Список категорий**
    1. Выводятся все категории для которых создано хотя бы одно объявление;
    2. Рядом с наименованием категории отображается количество объявлений, соответствующих категории;
    3. Изображение для категории выбирается случайным образом из директории изображений.
8. **Карточка объявления**
    
    Карточка объявления представлена набором информации:
    
    — Изображение;
    
    — Заголовок объявления;
    
    — Тип объявления (куплю/продам);
    
    — Список категорий. Каждая категория ссылка на страницу «Объявления категории»;
    
    — Стоимость;
    
    — Анонс, не более 55 символов.
    

### **Регистрация**

1. Страница доступна гостям и авторизованным пользователям;
2. На странице отображается ссылка на страницу «Вход»;
3. Регистрационная форма:
    
    — Имя и фамилия;
    
    — Электронная почта;
    
    — Пароль;
    
    — Повтор ввода пароля;
    
    — Аватар. Позволяет загружать изображения в формате jpg и png.
    
4. Все поля регистрационной формы обязательны для заполнения;
5. Ошибки валидации отображается для каждого поля;
6. В случае успешной регистрации пользователь перенаправляется на страницу «Вход», где ему предлагается ввести логин и пароль;
7. Правила валидации:
    
    — Имя и фамилия. Не должно содержать цифр и специальных символов;
    
    — Электронная почта. Уникальное значение (в базе не может быть двух пользователей с одинаковым email). Валидный адрес электронной почты;
    
    — Пароль. Не меньше 6 символов;
    
    — Повтор ввода пароля. Не меньше 6 символов. Совпадает со значением поля «Пароль»;
    
8. Текст сообщений при валидации остаётся на усмотрение разработчика.
9. **Вход/регистрация через «ВК»**
    
    Сайт также предоставляет альтернативный способ авторизации — вход через соц. сеть «Вконтакте». Для этого на форме входа размещается кнопка «Вход через ВК», которая ведёт на oauth сервер соц. сети. Весь дальнейший процесс получения информации о пользователя описан в [официальной документации](https://dev.vk.com/api/access-token/authcode-flow-user)
    
    Если полученный от ВК email пользователя уже существует в БД, то достаточно сохранить для этого пользователя дополнительную информацию и выполнить его аутентификацию. Если пользователя с указанным email нет в БД, то следует его зарегистрировать, используя полученные от ВК данные, а затем выполнить вход.
    
    Важно: пользователь, зарегистрированный через ВК, не имеет пароля, а значит не может поменять его
    

### **Вход**

1. Страница доступна гостям и авторизованным пользователям;
2. Форма форма:
    
    — Электронная почта;
    
    — Пароль.
    
3. Все поля формы входа обязательны для заполнения;
4. Ошибки валидации отображается для каждого поля;
5. В случае успешного входа пользователь перенаправляется на главную страницу;
6. При возникновении ошибки поле «Пароль» очищается. Выводится сообщение валидации;
7. Правила валидации:
    
    — Электронная почта. Электронный ящик должен существовать в БД;
    
    — Пароль. Пароль должен совпадать с паролем, сохранённым в БД;
    
8. Текст сообщений при валидации остаётся на усмотрение разработчика.

### **Объявление о продаже или покупке**

1. Страница доступна гостям и авторизованным пользователям.
2. Страница объявления представлена следующим набором информации:
    
    — Заголовок объявления;
    
    — Изображения объявления.
    
    — Стоимость;
    
    — Тип объявления (Куплю/Продам);
    
    — Текст объявления;
    
    — Дата публикации в формате `DD MMM YYYY`. Например: `18 декабря 2019`;
    
    — Имя и фамилия автора объявления;
    
    — Контактный email;
    
    — Список категорий. Для изображений категорий отображаются случайные изображения;
    
    — Список комментариев;
    
3. Авторизованным пользователям доступна форма отправки нового комментария.
4. **Комментарии**
    1. На странице объявления отображаются все комментарии;
    2. Порядок вывода комментариев: сначала новые;
    3.  Форма отправки нового комментария отображается только авторизованным пользователям;
    4.  Для гостей вместо формы отправки нового комментария выводится текст: «Отправка комментариев доступна только для зарегистрированных пользователей.» и ссылка «Вход и регистрация»;
    5. Каждый комментарий представлен набором информации:
        
        — Аватар автора комментария;
        
        — Имя и фамилия автора комментария;
        
        — Текст комментария.
        
    6. Правила валидации формы отправки нового комментария:
        
        — Текст комментария. Обязательно для заполнения. Минимум 20 символов;
        
    7. Текст сообщений при валидации остаётся на усмотрение разработчика;
    8. В случае успешной отправки комментарии пользователь возвращается на страницу публикации.
    
    1. Чат на странице объявления и его функционал.
    
    — Переписываться могут только продавец с покупателем на странице конкретного объявления
    — Чат работает в режиме “in realtime” (т.е. без перезагрузки страницы)
    — Необходимо использовать firebase realtime db и его sdk для php: https://firebase-php.readthedocs.io/en/stable/realtime-database.html
    — Со стороны php необходимо будет написать код, который подключается к этой БД и отправляет юзеру email уведомление, если у него есть непрочитанное сообщение в чате
    

### **Объявления категории**

1. Страница доступна гостям и авторизованным пользователям;
2. На странице отображаются объявления соответствующие выбранной категории;
3. Порядок сортировки: сначала новые;
4. На странице отображается список всех категорий;
5. На странице отображается заголовок выбранной категории, а также количество объявлений, которые ей соответствуют;
6. На странице отображается не больше 8 карточек с объявлениями;
7. Под карточками объявления отображается пейджер страниц;
8. Если нет объявлений для выбранной категории, то выводится текст «Объявления отсутствуют»;
9. Карточки объявлений, соответствующие выбранной категории, представлены в том же виде, что и на главной странице.
10. **Пейджер страниц**
    
    — Пейджер страниц отображается под списком карточек;
    
    — Пейджер формирует страницы для очередных 8 объявлений. Например, всего `10` предложений — в пейджере отображается `2` страницы. Всего `17` предложений — в пейджере отображается 3 страницы и так далее. На каждой странице отображается очередные `8` или оставшиеся предложения (если их меньше `8`);
    
    — Для текущей страницы ссылка в пейджере не отображается. Например, если пользователь находится на странице `2`, то ссылки будут на всех, кроме второй страницы.
    

### **Результаты поиска**

1. Страница доступна гостям и авторизованным пользователям;
2. На странице отображаются результаты поиска объявлений;
3. Поиск объявлений выполняется по наименованию. Объявление соответствует поиску в случае наличия хотя бы одного вхождения искомой фразы;
4. Порядок вывода результатов: сначала новые;
5. На странице результатов поиска отображается информация о количестве найденных публикаций;
6. При переходе на странице результатов поиска, поисковый запрос сохраняется в строке поиска;
7. Под результатами поиска отображается блок «Самое свежее» (он же отображается на главной странице);
8. Если не удалось найти объявлений, соответствующих поисковому запросу, отображается текст «Не найдено ни одной публикации».

### **Новое объявление**

1. Страница доступна только авторизованным пользователям;
2. Форма добавления нового объявления:
    
    — Изображения нового объявления. Позволяет загружать изображения в формате jpg и png;
    
    — Заголовок объявления;
    
    — Текст объявления;
    
    — Категории;
    
    — Стоимость;
    
    — Тип объявления (куплю/продам).
    
3. Все поля формы обязательны для заполнения;
4. В случае успешного добавления объявления пользователь переходит на страницу «Публикации»;
5. Правила валидации:
    
    — Заголовок объявления. Минимум 10 символов, максимум 100;
    
    — Текст объявления. Минимум 50 символов, максимум 1000;
    
    — Стоимость. Минимум 100.
    
6. Текст сообщений при валидации остаётся на усмотрение разработчика;
7. Ошибки валидации отображается для каждого поля.

### **Редактирование объявления**

1. На странице отображается форма аналогичная форме на странице «Новое объявление»;
2. Все поля формы обязательны для заполнения;
3. Правила валидации соответствуют аналогичным для страницы «Новое объявление»;
4. Текст сообщений при валидации остаётся на усмотрение разработчика;
5. Ошибки валидации отображается для каждого поля.

### **Публикации**

1. Страница доступна только авторизованным пользователям;
2. На странице отображаются все объявления, добавленные текущим пользователем;
3. Порядок вывода: сначала самые свежие;
4. Для каждого объявления отображается кнопка «Удалить». При нажатии на кнопку выполняется удаление выбранного объявления;
5. При удалении объявления, комментарии, которые к нему относятся также удаляются;
6. Ссылка «Новая публикация» перенаправляет пользователя на страницу «Новое объявление»;
7. Клик по заголовку объявления перенаправляет на страницу редактирования объявления.

### **Комментарии**

1. Страница доступна только авторизованным пользователям;
2. На странице отображаются комментарии ко всем публикациям пользователя;
3. Порядок вывода: сначала объявления с новыми комментариями;
4. Вывод комментарий осуществляется в разрезе объявления. Объявление представлено набором информации:
    
    — Заголовок объявления;
    
    — Стоимость;
    
    — Тип объявления.
    
5. Комментарий представлен набором информации:
    
    — Аватар пользователя;
    
    — Фамилия и имя;
    
    — Текст комментария;
    
    — Кнопка «Удалить».
    
6. Нажатие на кнопку «Удалить» приводит к удалению выбранного комментария.

### **Безопасность**

1. Пользователи не могут удалить комментарии с чужих объявлений. В случае попытки удаления, сервер возвращает код `403`;
2. Пользователи не могут удалять чужие объявления. В случае попытки удаления, сервер возвращает код `403`.

### **Страницы ошибок**

1. Реализованы страницы для отображения статусов ответов сервера для классов 4 (ошибки клиента) и 5 (ошибки сервера).

### **Роли пользователей**

1. Анонимные пользователи имеют доступ к просмотру всех объявлений, странице регистрации и модальному окну входа на сайт.
2. Пользователи, прошедшие регистрацию и вошедшие на сайт, видят все страницы, доступные для своей роли, кроме страницы регистрации и страницы входа.
3. Пользователи могут добавлять и редактировать свои объявления, а также публиковать комментарии к объявлениям других пользователей.
4. У модераторов есть возможность удаления комментариев и объявлений пользователей.

Инструкция по запуску проекта находится в файле INSTALL.md

Отправка уведомлений пользователям о количестве непрочитанных ими сообщений реализована через консоль контроллером app\commands\NotificationsController


<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Basic Project Template</h1>
    <br>
</p>

Yii 2 Basic Project Template is a skeleton [Yii 2](http://www.yiiframework.com/) application best for
rapidly creating small projects.

The template contains the basic features including user login/logout and a contact page.
It includes all commonly used configurations that would allow you to focus on adding new
features to your application.

[![Latest Stable Version](https://img.shields.io/packagist/v/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![Total Downloads](https://img.shields.io/packagist/dt/yiisoft/yii2-app-basic.svg)](https://packagist.org/packages/yiisoft/yii2-app-basic)
[![build](https://github.com/yiisoft/yii2-app-basic/workflows/build/badge.svg)](https://github.com/yiisoft/yii2-app-basic/actions?query=workflow%3Abuild)

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.4.


INSTALLATION
------------

### Install via Composer

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this project template using the following command:

~~~
composer create-project --prefer-dist yiisoft/yii2-app-basic basic
~~~

Now you should be able to access the application through the following URL, assuming `basic` is the directory
directly under the Web root.

~~~
http://localhost/basic/web/
~~~

### Install from an Archive File

Extract the archive file downloaded from [yiiframework.com](http://www.yiiframework.com/download/) to
a directory named `basic` that is directly under the Web root.

Set cookie validation key in `config/web.php` file to some random secret string:

```php
'request' => [
    // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
    'cookieValidationKey' => '<secret random string goes here>',
],
```

You can then access the application through the following URL:

~~~
http://localhost/basic/web/
~~~


### Install with Docker

Update your vendor packages

    docker-compose run --rm php composer update --prefer-dist
    
Run the installation triggers (creating cookie validation code)

    docker-compose run --rm php composer install    
    
Start the container

    docker-compose up -d
    
You can then access the application through the following URL:

    http://127.0.0.1:8000

**NOTES:** 
- Minimum required Docker engine version `17.04` for development (see [Performance tuning for volume mounts](https://docs.docker.com/docker-for-mac/osxfs-caching/))
- The default configuration uses a host-volume in your home directory `.docker-composer` for composer caches


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Tests are located in `tests` directory. They are developed with [Codeception PHP Testing Framework](http://codeception.com/).
By default, there are 3 test suites:

- `unit`
- `functional`
- `acceptance`

Tests can be executed by running

```
vendor/bin/codecept run
```

The command above will execute unit and functional tests. Unit tests are testing the system components, while functional
tests are for testing user interaction. Acceptance tests are disabled by default as they require additional setup since
they perform testing in real browser. 


### Running  acceptance tests

To execute acceptance tests do the following:  

1. Rename `tests/acceptance.suite.yml.example` to `tests/acceptance.suite.yml` to enable suite configuration

2. Replace `codeception/base` package in `composer.json` with `codeception/codeception` to install full-featured
   version of Codeception

3. Update dependencies with Composer 

    ```
    composer update  
    ```

4. Download [Selenium Server](http://www.seleniumhq.org/download/) and launch it:

    ```
    java -jar ~/selenium-server-standalone-x.xx.x.jar
    ```

    In case of using Selenium Server 3.0 with Firefox browser since v48 or Google Chrome since v53 you must download [GeckoDriver](https://github.com/mozilla/geckodriver/releases) or [ChromeDriver](https://sites.google.com/a/chromium.org/chromedriver/downloads) and launch Selenium with it:

    ```
    # for Firefox
    java -jar -Dwebdriver.gecko.driver=~/geckodriver ~/selenium-server-standalone-3.xx.x.jar
    
    # for Google Chrome
    java -jar -Dwebdriver.chrome.driver=~/chromedriver ~/selenium-server-standalone-3.xx.x.jar
    ``` 
    
    As an alternative way you can use already configured Docker container with older versions of Selenium and Firefox:
    
    ```
    docker run --net=host selenium/standalone-firefox:2.53.0
    ```

5. (Optional) Create `yii2basic_test` database and update it by applying migrations if you have them.

   ```
   tests/bin/yii migrate
   ```

   The database configuration can be found at `config/test_db.php`.


6. Start web server:

    ```
    tests/bin/yii serve
    ```

7. Now you can run all available tests

   ```
   # run all available tests
   vendor/bin/codecept run

   # run acceptance tests
   vendor/bin/codecept run acceptance

   # run only unit and functional tests
   vendor/bin/codecept run unit,functional
   ```

### Code coverage support

By default, code coverage is disabled in `codeception.yml` configuration file, you should uncomment needed rows to be able
to collect code coverage. You can run your tests and collect coverage with the following command:

```
#collect coverage for all tests
vendor/bin/codecept run --coverage --coverage-html --coverage-xml

#collect coverage only for unit tests
vendor/bin/codecept run unit --coverage --coverage-html --coverage-xml

#collect coverage for unit and functional tests
vendor/bin/codecept run functional,unit --coverage --coverage-html --coverage-xml
```

You can see code coverage output under the `tests/_output` directory.
