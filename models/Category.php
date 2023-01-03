<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $category_id
 * @property string $category_name
 * @property string|null $category_icon
 *
 * @property OfferCategory[] $offerCategories
 * @property Offer[] $offers
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_name'], 'required'],
            [['category_name'], 'string', 'max' => 30],
            [['category_icon'], 'string', 'max' => 255],
            [['category_icon'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'category_id' => 'ID категории',
            'category_name' => 'Название категории',
            'category_icon' => 'Иконка категории',
        ];
    }

    /**
     * Gets query for [[OfferCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOfferCategories()
    {
        return $this->hasMany(OfferCategory::class, ['category_id' => 'category_id']);
    }

    /**
     * Gets query for [[Offers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOffers()
    {
        return $this->hasMany(Offer::class, ['offer_id' => 'offer_id'])->viaTable('offer_category', ['category_id' => 'category_id']);
    }

    /**
     * Получение категории по её id
     *
     * @param int $id - id категории
     *
     * @return Category|null
     */
    public static function getCategory(int $id): ?Category
    {
        return self::findOne($id);
    }

}
