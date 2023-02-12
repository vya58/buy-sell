<?php

namespace app\models\forms;

use app\models\Comment;
use app\models\OfferComment;
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
    $comment->comment_text = $this->commentText;

    $transaction = Yii::$app->db->beginTransaction();

    try {
      if (!$comment->save()) {
        throw new DataSaveException('Не удалось создать комментарий');
      }
      $offerComment = new OfferComment();

      $offerComment->offer_id = $offerId;
      $offerComment->comment_id = $comment->comment_id;

      if (!$offerComment->save()) {
        throw new DataSaveException('Не удалось сохранить комментарий');
      }

      $transaction->commit();
    } catch (DataSaveException $exception) {
      $transaction->rollback();
      throw new DataSaveException($exception->getMessage());
    }
    return true;
  }
}
