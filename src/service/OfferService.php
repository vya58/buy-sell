<?php

namespace app\src\service;

use app\components\Firebase;
use app\models\Comment;
use app\models\Offer;
use app\models\OfferCategory;
use Yii;
use app\src\exceptions\DataSaveException;
use \yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;

/**
 * Класс обработки app\models\Offer
 */
class OfferService
{
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
   * Метод получения случайного изображения, принадлежащего объявлению, относящегося к категории с id, равным $categoryId
   *
   * @param OfferCategory $offerCategory модель класса OfferCategory
   *
   * @return string $image - путь к изображению случайного объявления
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
   * Метод удаления объявления с комментариями к нему
   * @param Offer $offer - модель класса Offer (Объявление)
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
      $firebase = new Firebase($offer->offer_id);
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
