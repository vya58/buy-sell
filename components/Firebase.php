<?php

namespace app\components;

use Yii;
use yii\base\Component;
use app\models\User;
use app\src\exceptions\DataSaveException;
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
class Firebase extends Component
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
   * @return string $path - Строка с путём к запрашиваемому значению в Firebase (Например: 'id объявления'/'id покупателя')
   */
  private function getQuery(): string
  {
    if (!$this->offerId) {
      return '';
    }

    $offerId = (string) $this->offerId;

    if (!$this->buyerId) {
      return $offerId;
    }
    $buyerId = (string) $this->buyerId;

    return $offerId . '/' . $buyerId;
  }

  /**
   * Получение данных чата
   *
   * @return null|array
   */
  public function getValueChat(): ?array
  {
    if (!$this->database) {
      return null;
    }
    return $this->database->getReference($this->getQuery())->getValue();
  }

  /**
   * Получение snapshot'а чата
   *
   * @return null|\Kreait\Firebase\Database\Snapshot
   */
  public function getSnapshotChat(): ?\Kreait\Firebase\Database\Snapshot
  {
    if (!$this->database) {
      return null;
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
    if (!$this->database || !$message || !isset($addressee->user_id)) {
      return null;
    }

    $query = $this->getQuery();

    $countMessages = (string) self::getSnapshotChat()->numChildren();

    $query = $query . '/' . $countMessages;

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
   * @param int $messageNumber номер сообщения (ключ индексированного массива, значение которого - сообщение в виде ассоциативного массива)
   *
   * @return bool
   * @throws DataSaveException
   */
  public function readMessage(int $messageNumber): bool
  {
    if (!$this->database) {
      return false;
    }

    $messageNumber = (string) $messageNumber;

    $query = $this->getQuery() . '/' . $messageNumber;

    try {
      $this->database->getReference($query)
        ->update([
          'read' => true,
        ]);
    } catch (DataSaveException $exception) {
      throw new DataSaveException('Не удалось отметить сообщение прочитанным');
    }
    return true;
  }

  /**
   * Удаление чата
   *
   * @return bool
   * @throws DataSaveException
   */
  public function deleteChat(): bool
  {
    if (!$this->database) {
      return false;
    }
    $query = $this->getQuery();

    try {
      $this->database->getReference($query)
        ->remove();
    } catch (DataSaveException $exception) {
      throw new DataSaveException('Не удалось удалить чат');
    }
    return true;
  }
}
