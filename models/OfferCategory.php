<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "offer_category".
 *
 * @property int $id
 * @property int $offer_id
 * @property int $category_id
 *
 * @property Category $category
 * @property Offer $offer
 */
class OfferCategory extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'offer_category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['offer_id', 'category_id'], 'required'],
            [['offer_id', 'category_id'], 'integer'],
            [['category_id', 'offer_id'], 'unique', 'targetAttribute' => ['category_id', 'offer_id']],
            [['offer_id'], 'exist', 'skipOnError' => true, 'targetClass' => Offer::class, 'targetAttribute' => ['offer_id' => 'offer_id']],
            [['category_id'], 'exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['category_id' => 'category_id']],
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
            'category_id' => 'ID категории',
        ];
    }

    /**
     * Gets query for [[Category]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(Category::class, ['category_id' => 'category_id']);
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
