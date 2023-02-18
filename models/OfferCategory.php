<?php

namespace app\models;

use \yii\db\ActiveQuery;

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
   *
   * @return string
   */
  public static function tableName(): string
  {
    return 'offer_category';
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   */
  public function rules(): array
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
   *
   * @return array
   */
  public function attributeLabels(): array
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
   * @return ActiveQuery
   */
  public function getCategory(): ActiveQuery
  {
    return $this->hasOne(Category::class, ['category_id' => 'category_id']);
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
