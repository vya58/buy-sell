<?php

namespace app\models;

use app\models\exceptions\DataSaveException;
use Yii;
use \yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

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
  public const OFFER_TYPE = [
    'buy' => 'КУПЛЮ',
    'sell' => 'ПРОДАМ',
  ];

  public const MIN_LENGTH_TICKET_NAME = 10;
  public const MAX_LENGTH_TICKET_NAME = 50;
  public const MIN_LENGTH_TICKET_COMMENT = 50;
  public const MAX_LENGTH_TICKET_COMMENT = 1000;
  public const MIN_TICKET_PRICE = 100;
  public const MAX_OFFER_TYPE = 10;
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

  /**
   * Метод получения случайного изображения, принадлежащего объявлению, относящегося к категории с id, равным $categoryId
   *
   * @param OfferCategory $offerCategory модель класса OfferCategory
   *
   * @return string $image - изображение случайного объявления
   */
  public static function getImageOfRandomOffers(OfferCategory $offerCategory): string
  {
    if (isset($offerCategory->category->category_id)) {
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
    }

    // Если объявление не имеет изображения, то повторяем процедуру
    if (!$image) {
      $image = self::getImageOfRandomOffers($offerCategory);
    };

    return $image;
  }

  /**
   * Получение объявлений, отсортированных по дате добавления (в начале - самые новые)
   *
   * @return ActiveQuery
   */
  public static function getNewOffers(): ActiveQuery
  {
    return Offer::find()
      ->with('categories');
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
      ->join('RIGHT JOIN', Comment::tableName() . ' oc', 'o.offer_id=oc.offer_id')
      ->groupBy('o.offer_id')
      ->orderBy(['countComments' => SORT_DESC])
      ->limit(Yii::$app->params['mostTalkedOffersCount'])
      ->all();
  }

  /**
   * Метод получения объявлений, отсортированных по дате добавления комментариев (в начале - объявления с новыми комментариями)
   *
   * @param int $userId - id пользователя, чьи объявления выводятся
   *
   * @return array|null массив объектов класса app\models\Offer либо null, если нет объявлений с комментариями
   */
  public static function getWithNewCommentsOffers(int $userId): ?array
  {
    return Offer::find()
      ->alias('o')
      ->with('owner')
      ->joinWith(
        [
          'comments' => function (ActiveQuery $query) {
            $query->orderBy('comment.comment_id DESC');
          }
        ],
        true,
        'INNER JOIN'
      )
      ->where(['o.owner_id' => $userId])
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
    // Получение комментариев, связанных с этим объявлением
    if (isset($offer->comments)) {
      $comments = $offer->comments;
    }

    // Получение чатов, связанных с этим объявлением
    if (isset($offer->offer_id)) {
      $firebase = new ChatFirebase($offer->offer_id);
    }

    $transaction = Yii::$app->db->beginTransaction();

    try {
      //Удаление комментариев к объявлению
      if (count($comments)) {
        foreach ($comments as $comment) {
          $comment->delete();
        }
      }
      if ($offer->delete() && $firebase) {
        //Удаление чатов объявления
        $firebase->deleteChat();
      }

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
   * @param int $categoryId - id категории, чьи объявления выводятся
   *
   * @return ActiveQuery
   */
  public static function getCategoryOffers($categoryId): ActiveQuery
  {
    return Offer::find()
      ->rightJoin('offer_category oc', '`oc`.`offer_id` = `offer`.`offer_id`')
      ->with('offerCategories', 'categories')
      ->where(['oc.category_id' => $categoryId]);
  }

  /**
   * Метод поиска объявлений по наименованию
   *
   * @param string $searchQuery - строка с фразой поиска
   *
   * @return ActiveQuery
   */
  public static function searchOffers($searchQuery): ActiveQuery
  {
    return Offer::find()
      ->with('categories')
      ->where(['like', 'offer_title', $searchQuery]);
  }
}
