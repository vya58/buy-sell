<?php

namespace app\models;

use \yii\db\ActiveQuery;
use \yii\db\ActiveRecord;

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
class OfferComment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public static function tableName(): string
    {
        return 'offer_comment';
    }

    /**
     * {@inheritdoc}
     *
     * @return array
     */
    public function rules(): array
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
     *
     * @return array
     */
    public function attributeLabels(): array
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
     * @return ActiveQuery
     */
    public function getComment(): ActiveQuery
    {
        return $this->hasOne(Comment::class, ['comment_id' => 'comment_id']);
    }

    /**
     * Gets query for [[Offer]].
     *
     * @return ActiveQuery
     */
    public function getOffer(): ActiveQuery
    {
        return $this->hasOne(Offer::class, ['offer_id' => 'offer_id']);
    }
}
