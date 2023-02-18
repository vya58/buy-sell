<?php

namespace app\src\service;

use app\components\Firebase;
use yii\data\ActiveDataProvider;
use app\models\User;
use app\models\Offer;
use app\models\forms\ChatForm;
use Yii;

/**
 * @property int $offerId - id публикации
 * @property int $buyerId - id покупателя
 *
 * @property $database - \Kreait\Firebase\Contract\Database
 */
class ChatService
{
  // Ключ выборки сообщений отправителя
  private const SENDER_MESSAGE_SELECTION_KEY = 'fromUserId';

  private $firebase;

  public function __construct(
    private readonly int $offerId,
    private readonly int $buyerId
  ) {
    $this->firebase = new Firebase($offerId, $buyerId);
  }

  /**
   * Метод получения провайдера данных для окна выбора чата продавца с покупателями
   *
   * @param Offer $offer - объявление, на странице которого открыт чат. Объект класса app\models\Offer
   * @param int|null $currentPage - номер текущей страницы пагинатора
   *
   * @return ActiveDataProvider|null $dataProvider - провайдер данных чата
   */
  public static function getDataProviderForChat(Offer $offer, int $currentPage = null): ?ActiveDataProvider
  {
    // Если пользователь не является владельцем объявления, то больше одного собеседника в чате объявления у него не будет. Значит постраничная разбивка собеседников не нужна
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      return null;
    }
    // Выборка всех сообщений объявления с данным id
    $firebase = new Firebase($offer->offer_id);
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
   * Метод получения id покупателя  в чате
   *
   * @return int $this->buyerId - id покупателя в чате
   */
  public function getBuyerId(): int
  {
    return $this->buyerId;
  }

  /**
   * Метод получения адресата сообщения в чате
   *
   * @param User $owner - владелец объявления
   *
   * @return User $addressee - адресат сообщения
   */
  public function getAddresse(User $owner): User
  {
    // Если страница не владельца объявления, то адресат сообщения - продавец
    if (\Yii::$app->user->id !== $owner->user_id) {
      return $owner;
    }
    // Иначе, адресат сообщения - покупатель с id === buyerId
    return User::findOne($this->buyerId);
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
  public function sendMessage(User $addressee, ChatForm $chatForm): ?array
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
