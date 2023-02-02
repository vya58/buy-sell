<?php

namespace app\controllers;

use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\ChatFirebase;
use app\models\Notification;

/*
* Запуск сбора неполученных сообщений пользователям в чате и отправка им e-mail-уведомлений об этом
* через web-страницу.
* Действие возможно только пользователю с правами модератора.
*
*/

class NotificationsController extends Controller
{
  /**
   * {@inheritdoc}
   */

  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'denyCallback' => function () {
          return $this->redirect(['site/index']);
        },
        'only' => ['index'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index'],
            'roles' => [\Yii::$app->user->can('moderator')],
          ],
        ]
      ]
    ];
  }

  /**
   * Действие по получению непрочитанных сообщений в Firebase и e-mail-рассылке писем их получателям с количеством непрочтенных сообщений
   *
   * @return Response|string - код страницы
   */
  public function actionIndex()
  {
    $firebase = new ChatFirebase();
    $firebaseAllOffersChats = $firebase->getValueChat();

    $result = [];

    // Выборка всех сообщений из Firebase, у которых ключ 'read' = false, т.е. сообщение не прочитано
    Notification::searchKey('read', $firebaseAllOffersChats, $result);

    // Сортировка всех непрочитанных сообщений в многомерный массив, где ключ первого уровня вложенности - id пользователя, которому адресовано непрочтённое сообщение. Значения, соответствующие этим ключам - массив с непрочитанными сообщениями этому пользователю
    $users = Notification::sortMessagesByRecipients($result);

    //Отправка писем пользователям с количеством непрочтенных сообщений
    foreach ($users as $key => $value) {
      $countMessages = count($users[$key]);
      Notification::sendEmail($key, $countMessages);
    }

    return $this->render('index');
  }
}
