<?php

namespace app\models\forms;

use app\models\ChatFirebase;
use app\models\User;
use yii\base\Model;

class ChatForm extends Model
{
  public string $message = '';

  /**
   * @inheritDoc
   * @return array
   */
  public function rules(): array
  {
    return [
      [['message'], 'string'],
    ];
  }

  /**
   * @inheritDoc
   * @return array
   */
  public function attributeLabels(): array
  {
    return [
      'message' => 'Ваше сообщение в чат',
    ];
  }

  /**
   * Метод сохранения данных из формы добавления публикации в БД
   *
   * @param User $addressee - объект класса User - адресат отправляемого сообщения
   * @param ChatFirebase|null $chatFirebase объект класса ChatFirebase или null
   *
   * @return bool
   */

  public function addMessage(User $addressee, ?ChatFirebase $chatFirebase = null): bool
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
