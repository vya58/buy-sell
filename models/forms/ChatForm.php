<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\ChatFirebase;
use app\models\exceptions\FileExistException;
use app\models\exceptions\DataSaveException;

class ChatForm extends Model
{
  public string $message = '';

  /**
   * @inheritDoc
   */
  public function rules(): array
  {
    return [
      [['message'], 'string'],
    ];
  }

  /**
   * @inheritDoc
   */
  public function attributeLabels()
  {
    return [
      'message' => 'Ваше сообщение в чат',
    ];
  }

  /**
   * Метод сохранения данных из формы добавления публикации в БД
   *
   * @param int $id - id объявления
   *
   * @return bool
   */

  public function addMessage($addressee, ?ChatFirebase $chatFirebase = null): bool
  {
    if (!$this->message || !$chatFirebase) {
      return false;
    }

    if ($chatFirebase->sendMessage($addressee, $this->message)) {
      return true;
    }
    return false;
  }
}
