<?php

namespace app\controllers;

use app\models\ChatFirebase;
use app\models\Notification;
use app\models\helpers\CalculateHelper;
use yii\filters\AccessControl;
use yii\web\Controller;

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
   */
  public function actionIndex()
  {
    $firebase = new ChatFirebase();
    $firebaseAllOffersChats = $firebase->getValueChat();

    $unreadMessages = [];
    // Ищем в чатах Firebase ключи 'read' со значением false (непрочтённые)
    $searchKey = 'read';
    $referenceValue = false;

    // Выборка всех сообщений из Firebase, у которых ключ 'read' = false, т.е. сообщение не прочитано
    if ($firebaseAllOffersChats) {
      CalculateHelper::searchKey($searchKey, $firebaseAllOffersChats, $unreadMessages, $referenceValue);
    }

    // Сортировка всех непрочитанных сообщений в многомерный массив, где ключ первого уровня вложенности - id пользователя, которому адресовано непрочтённое сообщение. Значения, соответствующие этим ключам - массив с непрочитанными сообщениями этому пользователю
    $addressees = Notification::sortMessagesByRecipients($unreadMessages);

    //Отправка писем пользователям с количеством непрочтенных сообщений
    foreach ($addressees as $key => $value) {
      $countMessages = count($addressees[$key]);
      if (isset($key)) {
        Notification::sendEmail($key, $countMessages);
      }
    }
    return $this->render('index');
  }
}
