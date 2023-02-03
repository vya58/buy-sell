<?php

namespace app\models;

use \yii\db\ActiveQuery;
use \yii\db\ActiveRecord;

/**
 * This is the model class for table "comment".
 *
 * @property int $comment_id
 * @property int $owner_id
 * @property string $comment_text
 *
 * @property OfferComment[] $offerComments
 * @property Offer[] $offers
 * @property User $owner
 */
class Comment extends ActiveRecord
{
  public const MIN_LENGTH_COMMENT = 20;

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function tableName(): string
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            [['comment_text'], 'required'],
            [['owner_id'], 'integer'],
            [['comment_text'], 'string'],
            [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'user_id']],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'comment_id' => 'ID комментария',
            'owner_id' => 'ID пользователя',
            'comment_text' => 'Текст комментария',
        ];
    }

    /**
     * Gets query for [[OfferComments]].
     *
     * @return ActiveQuery
     */
    public function getOfferComments(): ActiveQuery
    {
        return $this->hasMany(OfferComment::class, ['comment_id' => 'comment_id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return ActiveQuery
     */
    public function getOffers(): ActiveQuery
    {
        return $this->hasMany(Offer::class, ['offer_id' => 'offer_id'])->viaTable('offer_comment', ['comment_id' => 'comment_id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return ActiveQuery
     */
    public function getOwner(): ActiveQuery
    {
        return $this->hasOne(User::class, ['user_id' => 'owner_id']);
    }
}
