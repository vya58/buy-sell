<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use app\models\OfferComment;
use app\models\ChatFirebase;
use app\models\Comment;
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
      //[['message'], 'required', 'message' => 'Обязательное поле'],
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
   * @throws DataSaveException
   * @throws FileExistException
   */

  public function addMessage(ChatFirebase $chatFirebase): bool
  {
    //\yii\helpers\VarDumper::dump($this->message, 3, true);
    //die;
    if (!$this->message) {
      return false;
    }

    $userId = Yii::$app->user->id;
//\yii\helpers\VarDumper::dump($chatFirebase, 3, true);
   //die;
    //$chatFirebase = new ChatFirebase($offerId, $ownerOfferId, $buyerId); // М.б. вынести из метода?

    if ($chatFirebase->sendMessage($userId, $this->message)) {
      return true;
    }
    return false;
  }
}
