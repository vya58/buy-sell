<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database\Reference;

/**
 *
 */
class ChatFirebase extends Model
{
  private $database;

  public function __construct(
    private readonly ?int $offerId = null,
    private readonly ?int $buyerId = null
  ) {
    $this->database = (new Factory)
      ->withServiceAccount(Yii::$app->params['firebaseServiceAccountShape'] . 'firebase-adminsdk-4k4m2-1c314d0e34.json')
      ->withDatabaseUri(Yii::$app->params['firebaseDatabaseUri'])->createDatabase();
  }

  /**
   * Получение параметра запроса для Kreait\Firebase\Database\Reference в зависимости от наличия и значений id объявления и покупателя
   * @return string $path
   */
  private function getQuery(): string
  {
    if (!$this->offerId) {
      return '';
    }

    if (!$this->buyerId) {
      return $this->offerId;
    }
    return $this->offerId . '/' . $this->buyerId;
  }

  /**
   * Получение данных чата
   */
  public function getValueChat()
  {
    if (!$this->database) {
      return false;
    }
    return $this->database->getReference($this->getQuery())->getValue();
  }

  /**
   * Получение snapshot'а чата
   */
  public function getSnapshotChat()
  {
    if (!$this->database) {
      return false;
    }
    return $this->database->getReference($this->getQuery())->getSnapshot();
  }

  /**
   * Запись сообщения в Firebase
   * @return null|Reference
   */
  public function sendMessage(User $addressee, ?string $message = null): ?Reference
  {
    if (!$this->database || !$message) {
      return false;
    }

    $query = $this->getQuery();
    $count = self::getSnapshotChat()->numChildren();

    $query = $query . '/' . $count;
    return $this->database->getReference($query)
      ->set([
        'date' => date('d M Y H:i:s'),
        'message' => $message,
        'read' => false,
        // Ключи ниже добавлены для упрощения выборки непрочитанных сообщений и селекци адресатов
        'offerId' => $this->offerId,
        'toUserId' => $addressee->user_id,
        'fromUserId' => Yii::$app->user->id,
      ]);
  }

  /**
   * Отметка сообщения как прочитанного
   * @return null|Reference
   */
  public function readMessage(int $messageNumber): Reference
  {
    if (!$this->database) {
      return false;
    }

    $query = $this->getQuery() . '/' . $messageNumber;

    return $this->database->getReference($query)
      ->update([
        'read' => true,
      ]);
  }
}
