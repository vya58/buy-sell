<?php

namespace app\models;

use Yii;
use yii\base\Model;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Database\Reference;

/**
 * Класс модели для чата в Firebase Realtime Database
 * https://firebase-php.readthedocs.io/en/stable/realtime-database.html
 *
 * Схема базы данных чата в Firebase Realtime Database:
 *
 * Проект BuysellChat => [
 *    'id объявления' => [
 *        'id покупателя' => [
 *            0 => [
 *                'date' - дата и время сообщения в формате: 'd M Y H:i:s',
 *                 'message' - текст сообщения,
 *                 'read' - метка о прочтении получателем сообщения true/false,
 *
 *                  Ключи ниже добавлены для упрощения выборки непрочитанных сообщений и селекци адресатов
 *                  'offerId' - id объявления,
 *                  'toUserId' - id получателя сообщения,
 *                  'fromUserId' - id отправителя сообщения,
 *              ],
 *            1 => [
 *                ...
 *              ],
 *            ...
 *            ...
 *            n => [
 *                ...
 *              ],
 *          ]
 *      ]
 * ]
 *
 * @property int|null $offerId - id публикации
 * @property int|null $buyerId - id покупателя
 *
 * @property $database - \Kreait\Firebase\Contract\Database
 */
class ChatFirebase extends Model
{
  private $database;

  public function __construct(
    private readonly ?int $offerId = null,
    private readonly ?int $buyerId = null
  ) {
    $this->database = (new Factory)
      ->withServiceAccount(Yii::$app->params['firebaseServiceAccountShape'] . '.json')
      ->withDatabaseUri(Yii::$app->params['firebaseDatabaseUri'])->createDatabase();
  }

  /**
   * Получение параметра запроса для Kreait\Firebase\Database\Reference в зависимости от наличия и значений id объявления и покупателя
   *
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
   *
   * @return array|null
   */
  public function getValueChat(): ?array
  {
    if (!$this->database) {
      return false;
    }
    return $this->database->getReference($this->getQuery())->getValue();
  }

  /**
   * Получение snapshot'а чата
   *
   * @return false|\Kreait\Firebase\Database\Snapshot
   */
  public function getSnapshotChat(): false|\Kreait\Firebase\Database\Snapshot
  {
    if (!$this->database) {
      return false;
    }
    return $this->database->getReference($this->getQuery())->getSnapshot();
  }

  /**
   * Запись сообщения в Firebase
   *
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
   *
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

  public function deleteChat(): Reference
  {
    if (!$this->database) {
      return false;
    }
    $query = $this->getQuery();

    return $this->database->getReference($query)
      ->remove();
  }
}
