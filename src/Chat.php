<?php

namespace app\src;

use app\components\Firebase;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\forms\ChatForm;
use Yii;

class Chat
{
  // Ключ выборки сообщений отправителя
  private const SENDER_MESSAGE_SELECTION_KEY = 'fromUserId';

  private $firebase;

  public function __construct(
    private readonly int $id,
    private readonly int $buyerId
  ) {
    $this->firebase = new Firebase($id, $buyerId);
  }

  /**
   * Метод получения провайдера данных для окна выбора чата продавца с покупателями
   *
   * @param  int $id - id объявления на странице которого открыт чат
   * @param int|null $currentPage - номер текущей страницы пагинатора
   *
   * @return ActiveDataProvider $dataProvider - провайдер данных чата
   */
  public static function getDataProviderForChat(int $id, int $currentPage = null): ActiveDataProvider
  {
    // Выборка всех сообщений объявления с данным id
    $firebase = new Firebase($id);
    $firebaseChats = $firebase->getValueChat();

    $userIds = [];
    if ($firebaseChats) {
      foreach ($firebaseChats as $key => $value) {
        $userIds[] = $key;
      }
    }

    // Установка начала пагинации, чтобы в меню выбора пользователя для чата отображался выбранный пользователь
    if (isset(Yii::$app->request->queryParams['page'])) {
      $currentPage = Yii::$app->request->queryParams['page'] - 1;
    }

    $query = User::find()
      ->having(['in', 'user_id', $userIds]);

    return new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => 1,
        'page' => $currentPage,
      ],
    ]);
  }

  /**
   * Метод получения сообщений чата конкретного объявления с конкретным покупателем
   *
   * @return array|null $messages - массив с сообщениями чата или null
   */
  public function getBuyerChat(): ?array
  {
    $messages = $this->firebase->getValueChat();

    if ($messages) {
      foreach ($messages as $key => $message) {

        // Отметка после получения всех непрочтённых сообщений как прочитанных
        if (isset($message[self::SENDER_MESSAGE_SELECTION_KEY]) && $message[self::SENDER_MESSAGE_SELECTION_KEY] !== Yii::$app->user->id) {
          $this->firebase->readMessage($key);
        }
      }
    }
    return $messages;
  }

  /**
   * Метод отправки сообщений в чат
   *
   * @param User $addressee - объект класса User - адресат отправляемого сообщения
   * @param ChatForm $chatForm - форма отправки сообщения, объект класса ChatForm
   *
   * @return array|null $messages - массив с сообщениями чата или null
   */
  public function sendingMessage(User $addressee, ChatForm $chatForm): ?array
  {
    if (!$chatForm->getMessage()) {
      return null;
    }

    if ($this->firebase->sendMessage($addressee, $chatForm->getMessage())) {
      $messages = $this->firebase->getValueChat();

      return $messages;
    }
    return null;
  }
}
