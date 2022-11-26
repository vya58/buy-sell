<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "offer_comment".
 *
 * @property int $id
 * @property int $offer_id
 * @property int $comment_id
 *
 * @property Comment $comment
 * @property Offer $offer
 */
class OfferComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer_comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['offer_id', 'comment_id'], 'required'],
            [['offer_id', 'comment_id'], 'integer'],
            [['offer_id', 'comment_id'], 'unique', 'targetAttribute' => ['offer_id', 'comment_id']],
            [['offer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Offer::class, 'targetAttribute' => ['offer_id' => 'offer_id']],
            [['comment_id'], 'exist', 'skipOnError' => true, 'targetClass' => Comment::class, 'targetAttribute' => ['comment_id' => 'comment_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'offer_id' => 'ID объявления',
            'comment_id' => 'ID комментария',
        ];
    }

    /**
     * Gets query for [[Comment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComment()
    {
        return $this->hasOne(Comment::class, ['comment_id' => 'comment_id']);
    }

    /**
     * Gets query for [[Offer]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffer()
    {
        return $this->hasOne(Offer::class, ['offer_id' => 'offer_id']);
    }
}
