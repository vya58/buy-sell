<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;
use \yii\db\ActiveQuery;
use yii\web\Response;
use app\models\exceptions\DataSaveException;

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
 * @property User $owner
 */
class Offer extends \yii\db\ActiveRecord
{
  public const OFFER_TYPE = [
    'buy' => 'КУПЛЮ',
    'sell' => 'ПРОДАМ',
  ];
  //public const MIN_LENGTH_SEARCH_TICKETPHRASE = 3;
  public const MIN_LENGTH_TICKETNAME = 10;
  public const MAX_LENGTH_TICKETNAME = 100;
  public const MIN_LENGTH_TICKETCOMMENT = 50;
  public const MAX_LENGTH_TICKETCOMMENT = 1000;
  public const MIN_TICKETPRICE = 100;
  public const OFFER_IMAGE_UPLOAD_PATH = '/uploads/img/';

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
      [['owner_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['owner_id' => 'user_id']],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
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

  /**
   * Gets query for [[Owner]].
   *
   * @return \yii\db\ActiveQuery
   */
  public function getOwner()
  {
    return $this->hasOne(User::class, ['user_id' => 'owner_id']);
  }

  /**
   * Метод получения случайного изображения, принадлежащего объявлению, относящегося к категории с id, равным $categoryId
   *
   * @param OfferCategory $offerCategory модель класса OfferCategory
   *
   * @return string $image - изображение случайного объявления
   */
  public static function getImageOfRandomOffers(OfferCategory $offerCategory): string
  {
    // Вычисляем количество объявлений с данной категорией
    $countOffersInCategory = $offerCategory->getCountOffersInCategory($offerCategory->category->category_id);

    // Получаем случайное число в диапазоне, не превышающем количество объявлений с данной категорией
    $range = rand(0, $countOffersInCategory - 1);

    // Выбираем по случайное объявление, соответствующее данной категории
    $randomOffer = Offer::find()
      ->rightJoin('offer_category oc', '`oc`.`offer_id` = `offer`.`offer_id`')
      ->where(['oc.category_id' => $offerCategory->category->category_id])
      ->limit(1)
      ->offset($range)
      ->all();

    // Получаем изображение объявления
    $image = ArrayHelper::getValue($randomOffer, '0.offer_image');

    // Если объявление не имеет изображения, то повторяем процедуру
    if (!$image) {
      $image = self::getImageOfRandomOffers($offerCategory);
    };

    return $image;
  }

  /**
   * Метод получения объявлений, отсортированных по дате добавления (в начале - самые новые)
   *
   * @return array|null массив объектов класса app\models\Offer либо null, если объявлений нет
   */
  public static function getNewOffers(): ?array
  {
    return Offer::find()
      ->with('categories')
      ->orderBy(['offer_date_create' => SORT_DESC])
      ->limit(Yii::$app->params['newOffersCount'])
      ->all();
  }

  /**
   * Метод получения объявлений, отсортированных по количеству комментариев (в начале - самые комментируемые)
   *
   * @return array|null массив объектов класса app\models\Offer либо null, если нет объявлений с комментариями
   */
  public static function getMostTalkedOffers(): ?array
  {
    return Offer::find()
      ->alias('o')
      ->select(['o.*', 'COUNT(oc.offer_id) AS countComments'])
      ->join('RIGHT JOIN', OfferComment::tableName() . ' oc', 'o.offer_id=oc.offer_id')
      ->groupBy('o.offer_id')
      ->orderBy(['countComments' => SORT_DESC])
      ->limit(Yii::$app->params['mostTalkedOffersCount'])
      ->all();
  }

  /**
   * Метод получения объявлений, отсортированных по дате добавления комментариев (в начале - объявления с новыми комментариями)
   *
   * @param int $id - id пользователя, чьи объявления выводятся
   *
   * @return array|null массив объектов класса app\models\Offer либо null, если нет объявлений с комментариями
   */
  public static function getWithNewCommentsOffers(int $id): ?array
  {
    return Offer::find()
      ->with('owner', 'comments')
      ->joinWith(
        [
          'offerComments' => function (ActiveQuery $query) {
            $query->orderBy('offer_comment.id DESC');
          }
        ],
        true,
        'INNER JOIN'
      )
      ->where(['owner_id' => $id])
      ->all();
  }

  /**
   * Метод удаления объявления с комментариями к нему
   *
   * @return bool
   * @throws DataSaveException
   */
  public static function deleteOffer(Offer $offer): bool
  {
    $comments = $offer->comments;

    $transaction = Yii::$app->db->beginTransaction();

    try {
      foreach ($comments as $comment) {
        $comment->delete();
      }
      $offer->delete();

      $transaction->commit();
    } catch (DataSaveException $exception) {
      $transaction->rollback();
      throw new DataSaveException($exception->getMessage('Ошибка удаления объявления'));
    }
    return true;
  }

  /**
   * Метод запроса всех объявлений отдельной категории для датапровайдера
   *
   * @param int $id - id категории, чьи объявления выводятся
   *
   * @return ActiveQuery|null объект класса yii\db\ActiveQuery либо null, если нет объявлений данной категории
   */
  public static function getCategoryOffers($id): ?ActiveQuery
  {
    return Offer::find()
      ->rightJoin('offer_category oc', '`oc`.`offer_id` = `offer`.`offer_id`')
      ->with('offerCategories', 'categories')
      ->where(['oc.category_id' => $id]);
  }

  /**
   * Метод поиска объявлений по наименованию
   *
   * @param string $query - строка с фразой поиска
   *
   * @return ActiveQuery|null объект класса yii\db\ActiveQuery либо null, если нет объявлений данной категории
   */
  public static function searchOffers($query): ?ActiveQuery
  {
    return Offer::find()
      ->with('categories')
      ->where(['like', 'offer_title', $query]);
      //->orderBy(['offer_date_create' => SORT_DESC])
      //->all();
  }
}
