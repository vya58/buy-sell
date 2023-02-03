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
  /**
   * Метод поиска элементов многомерного массива по ключу c требуемым значением
   * Взято и переработано здесь: https://ru.stackoverflow.com/questions/806243/%D0%9F%D0%BE%D0%B8%D1%81%D0%BA-%D0%BA%D0%BB%D1%8E%D1%87%D0%B0-%D0%B2-%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE%D0%BC%D0%B5%D1%80%D0%BD%D0%BE%D0%BC-%D0%BC%D0%B0%D1%81%D1%81%D0%B8%D0%B2%D0%B5-php
   * @param string $searchKey Ключ который ищем
   * @param array $arr Массив в котором ищем
   * @param array $result Массив в который будет складываться результат (передается по ссылке) перед использованием - обнулить $result = []
   */
  public static function searchKey($searchKey, array $array, array &$result, $searchValue = false): void
  {
    $value = $searchValue;
    // Если в массиве есть элемент с ключем $searchKey и он пустой , то кладём сообщение в результат
    if (isset($array[$searchKey]) && $array[$searchKey] === $value) {
      $result[] = $array;
    }
    // Обходим все элементы массива в цикле
    foreach ($array as $key => $param) {
      // Если элемент массива - массив, то вызываем рекурсивно эту функцию
      if (is_array($param)) {
        self::searchKey($searchKey, $param, $result, $value);
      }
    }
  }

  /**
   * Метод отправки e-mail пользователю с количеством непрочитанных сообщений
   * @param int $toUserId id пользователя, которому производится отправка
   * @param int $countMessages Количество непрочитанных сообщений
   */
  public static function sendEmail(int $toUserId, int $countMessages): void
  {
    $recipient = User::findOne($toUserId);

    //Адрес email для отправки писем с сервера
    $emailSendServer = Yii::$app->params['buysellEmail'];
    $transport = Transport::fromDsn(Yii::$app->params['mailerDsn']);
    $message = [
      'from' => Yii::$app->params['buysellEmail'],
      'to' => $recipient->email,
      'subject' => 'Уведомление сервиса BuySell',
      'text' => $recipient->name . ', у вас ' . $countMessages . ' непрочитанных сообщений от пользователей сервиса BuySell',
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

  /**
   * Метод сортировки массива с сообщениями по id получателя
   *
   * @param array $array Сортируемый массив
   *
   * @return array $groups Vногомерный массив, где ключ первого уровня вложенности - id пользователя, которому адресовано непрочтённое сообщение. Значения, соответствующие этим ключам - массив с непрочитанными сообщениями этому пользователю
   */
  public static function sortMessagesByRecipients(array $array): array
  {
    $groups = [];
    foreach ($array as $element) {
      $id = $element['toUserId'];

      if (!array_key_exists($id, $groups)) {
        $groups[$id] = [];
      }

      $groups[$id][] = $element;
    }
    return $groups;
  }
}
