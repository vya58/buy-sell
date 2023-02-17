<?php

namespace app\models\forms;

use app\components\Firebase;
use app\src\Chat;
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
   * Метод получения сообщения из формы
   *
   * @return string сообщение из формы
   */
  public function getMessage()
  {
    return $this->message;
  }
}
