<?php

namespace app\models;

use \yii\db\ActiveQuery;

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
 * @property User $owner
 */
class Offer extends \yii\db\ActiveRecord
{
  // Тип объявления
  public const OFFER_TYPE = [
    'buy' => 'КУПЛЮ',
    'sell' => 'ПРОДАМ',
  ];

  public const MIN_LENGTH_TICKET_NAME = 10;
  public const MAX_LENGTH_TICKET_NAME = 50;
  public const MIN_LENGTH_TICKET_COMMENT = 50;
  public const MAX_LENGTH_TICKET_COMMENT = 1000;
  public const MIN_TICKET_PRICE = 100;
  public const OFFER_IMAGE_UPLOAD_PATH = '/uploads/img/';
  public const OFFER_IMAGE_STAB_PATH = '/img/blank.png';
  public const MAX_LENGTH_OFFER_IMAGE_UPLOAD_PATH = 255;

  /**
   * {@inheritdoc}
   *
   * @return string
   */
  public static function tableName(): string
  {
    return 'offer';
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['owner_id', 'offer_title', 'offer_price', 'offer_type', 'offer_text'], 'required'],
      [['owner_id', 'offer_price'], 'integer'],
      [['offer_text'], 'string'],
      [['offer_date_create'], 'safe'],
      [['offer_title'], 'string', 'max' => self::MAX_LENGTH_TICKET_NAME],
      [['offer_image'], 'string', 'max' => self::MAX_LENGTH_OFFER_IMAGE_UPLOAD_PATH],
      [['offer_type'], 'in', 'range' => self::OFFER_TYPE],
      [['offer_image'], 'unique'],
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
      'offer_id' => 'ID объявления',
      'owner_id' => 'ID автора объявления',
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
   * @return ActiveQuery
   */
  public function getCategories(): ActiveQuery
  {
    return $this->hasMany(Category::class, ['category_id' => 'category_id'])->viaTable('offer_category', ['offer_id' => 'offer_id']);
  }

  /**
   * Gets query for [[Comments]].
   *
   * @return ActiveQuery
   */
  public function getComments(): ActiveQuery
  {
    return $this->hasMany(Comment::class, ['offer_id' => 'offer_id']);
  }

  /**
   * Gets query for [[OfferCategories]].
   *
   * @return ActiveQuery
   */
  public function getOfferCategories(): ActiveQuery
  {
    return $this->hasMany(OfferCategory::class, ['offer_id' => 'offer_id']);
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
