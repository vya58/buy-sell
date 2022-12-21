<?php

namespace app\models;

use Yii;

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
class Comment extends \yii\db\ActiveRecord
{
  public const MIN_LENGTH_COMMENT = 20;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
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
     */
    public function attributeLabels()
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
     * @return \yii\db\ActiveQuery
     */
    public function getOfferComments()
    {
        return $this->hasMany(OfferComment::class, ['comment_id' => 'comment_id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::class, ['offer_id' => 'offer_id'])->viaTable('offer_comment', ['comment_id' => 'comment_id']);
    }

    /**
     * Gets query for [[Owner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOwner()
    {
        return $this->hasOne(User::class, ['user_id' => 'owner_id']);
    }
}
