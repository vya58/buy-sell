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
  private string $query;
  // private int $buyerId = null;

  public function __construct(
    private readonly int $offerId,
    private readonly ?int $buyerId = null
  ) {
    $this->database = (new Factory)
      ->withServiceAccount(Yii::$app->params['firebase_service_account_shape'] . 'firebase-adminsdk-4k4m2-1c314d0e34.json')
      ->withDatabaseUri(Yii::$app->params['firebase_database_uri'])->createDatabase();
  }


  /**
   * Получение параметра запроса для Kreait\Firebase\Database\Reference в зависимости от наличия и значения id покупателя
   * @return string $path
   */
  private function getQuery(): string
  {
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
   * Получение данных чата
   */
  public function getSnapshotChat()
  {
    if (!$this->database) {
      return false;
    }
    return $this->database->getReference($this->getQuery())->getSnapshot();
  }

  /**
   * Создание чата
   */
  /*
  public function setChat()
  {
    if (!$this->database) {
      return false;
    }
    return $this->database->getReference($this->query)
      ->set([
        [
          'userId' => 20,
          'date' => date('d M Y H:i:s'),
          'message' => 'Привет',
        ],
        [
          'userId' => 15,
          'date' => date('d M Y H:i:s'),
          'message' => 'Привет',
        ],
        [
          'userId' => 20,
          'date' => date('d M Y H:i:s'),
          'message' => 'Купи слона',
        ],
        [
          'userId' => 15,
          'date' => date('d M Y H:i:s'),
          'message' => 'Почём?',
        ]
      ]);
  }
*/
  /**
   * Запись сообщения в Firebase
   * @return null|Reference
   */
  public function sendMessage(int $userId, ?string $message = null): ?Reference
  {
    if (!$this->database || !$message) {
      return false;
    }

    $query = $this->getQuery();
    $count = self::getSnapshotChat()->numChildren();

    $query = $query . '/' . $count;
    return $this->database->getReference($query)
      ->set([
        'userId' => $userId,
        'date' => date('d M Y H:i:s'),
        'message' => $message,
      ]);
  }
}
