<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "comment".
 *
 * @property int $comment_id
 * @property string $comment_text
 *
 * @property OfferComment[] $offerComments
 * @property Offer[] $offers
 */
class Comment extends \yii\db\ActiveRecord
{
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
            [['comment_text'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'comment_id' => 'ID комментария',
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
}
