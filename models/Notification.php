<?php

namespace app\models;

use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use Yii;
use yii\base\Model;

/**
 * Класс модели уведомления пользователя о непрочитанных сообщениях
 */
class Notification extends Model
{
  private const SORTED_VALUE = 'toUserId';

  /**
   * Метод отправки e-mail пользователю с количеством непрочитанных сообщений
   * @param int $toUserId id пользователя, которому производится отправка
   * @param int $countMessages Количество непрочитанных сообщений
   */
  public static function sendEmail(int $toUserId, int $countMessages): void
  {
    $recipient = User::findOne($toUserId);

    if ($recipient && isset($recipient->email)) {
      //Адрес email для отправки писем с сервера
      $emailSendServer = Yii::$app->params['buysellEmail'];
      $transport = Transport::fromDsn(Yii::$app->params['mailerDsn']);
      $countMessages = (string) $countMessages;

      if (!isset($recipient->name)) {
        $recipientName = 'Уважаемый пользователь';
      }

      $recipientName = $recipient->name;

      $message = [
        'from' => Yii::$app->params['buysellEmail'],
        'to' => $recipient->email,
        'subject' => 'Уведомление сервиса BuySell',
        'text' => $recipientName . ', у вас ' . $countMessages . ' непрочитанных сообщений от пользователей сервиса BuySell',
      ];

      $mail = new Email();

      $mail->to($message['to']);
      $mail->from($emailSendServer);
      $mail->subject($message['subject']);
      $mail->html($message['text']);

      // Отправка сообщения
      $mailer = new Mailer($transport);
      $mailer->send($mail);
    }
  }

  /**
   * Метод сортировки массива с сообщениями по id получателя
   *
   * @param array $unreadMessages Сортируемый массив с непрочтенными сообщениями
   *
   * @return array $groups Многомерный массив, где ключ первого уровня вложенности, $addresseeId - id пользователя, которому адресовано непрочтённое сообщение. Значения, соответствующие этим ключам - массив с непрочитанными сообщениями этому пользователю
   */
  public static function sortMessagesByRecipients(array $unreadMessages): array
  {
    $groups = [];

    foreach ($unreadMessages as $element) {
      if (array_key_exists(Notification::SORTED_VALUE, $element)) {
        $addresseeId = $element[Notification::SORTED_VALUE];
      }

      if (!array_key_exists($addresseeId, $groups)) {
        $groups[$addresseeId] = [];
      }

      $groups[$addresseeId][] = $element;
    }
    return $groups;
  }
}
