<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "offer".
 *
 * @property int $offer_id
 * @property int $owner_id
 * @property string $offer_title
 * @property string|null $offer_image
 * @property int $offer_price
 * @property string $offer_type
 * @property string $offer_text
 * @property string $offer_date_create
 *
 * @property Category[] $categories
 * @property Comment[] $comments
 * @property OfferCategory[] $offerCategories
 * @property OfferComment[] $offerComments
 */
class Offer extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['owner_id', 'offer_title', 'offer_price', 'offer_type', 'offer_text'], 'required'],
            [['owner_id', 'offer_price'], 'integer'],
            [['offer_text'], 'string'],
            [['offer_date_create'], 'safe'],
            [['offer_title'], 'string', 'max' => 50],
            [['offer_image'], 'string', 'max' => 255],
            [['offer_type'], 'string', 'max' => 10],
            [['offer_image'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'offer_id' => 'ID объявления',
            'owner_id' => 'ID владельца объявления',
            'offer_title' => 'Титл объявления',
            'offer_image' => 'Картинка объявления',
            'offer_price' => 'Цена объявления',
            'offer_type' => 'Тип объявления',
            'offer_text' => 'Текст объявления',
            'offer_date_create' => 'Дата создания объявления',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['category_id' => 'category_id'])->viaTable('offer_category', ['offer_id' => 'offer_id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['comment_id' => 'comment_id'])->viaTable('offer_comment', ['offer_id' => 'offer_id']);
    }

    /**
     * Gets query for [[OfferCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOfferCategories()
    {
        return $this->hasMany(OfferCategory::class, ['offer_id' => 'offer_id']);
    }

    /**
     * Gets query for [[OfferComments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOfferComments()
    {
        return $this->hasMany(OfferComment::class, ['offer_id' => 'offer_id']);
    }
}
