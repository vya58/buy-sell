<?php

namespace app\models\forms;

use app\models\Comment;
use app\models\exceptions\DataSaveException;
use Yii;
use yii\base\Model;

class CommentAddForm extends Model
{
  public string $commentText = '';

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['commentText'], 'required', 'message' => 'Обязательное поле'],
      [['commentText'], 'string', 'min' => Comment::MIN_LENGTH_COMMENT],
    ];
  }

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function attributeLabels()
  {
    return [
      'commentText' => 'Текст комментария',
    ];
  }

  /**
   * Метод сохранения данных из формы добавления публикации в БД
   *
   * @param int $offerId - id объявления
   *
   * @return bool
   * @throws DataSaveException
   */
  public function addComment($offerId): bool
  {
    $comment = new Comment();

    $comment->owner_id = Yii::$app->user->id;
    $comment->offer_id = $offerId;
    $comment->comment_text = $this->commentText;

    if (!$comment->save()) {
      throw new DataSaveException('Не удалось создать комментарий');
    }
    return true;
  }
}
