<?php

namespace app\controllers;

use app\models\Category;
use app\models\Offer;
use app\models\OfferCategory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;


class CategoriesController extends Controller
{
  /**
   * Страница просмотра объявлений соответствующей категории
   *
   * @param int $categoryId - id категории
   * @return Response|string - код страницы
   */
  public function actionIndex(int $categoryId): Response|string
  {
    $query = Offer::getCategoryOffers($categoryId);

    $countOffers = $query->count();

    if (!$countOffers) {
      return $this->goHome();
    }

    $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
        'pageSize' => Yii::$app->params['pageSize'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);

    // Категории для section class="categories-list"
    $offerCategories = OfferCategory::getOfferCategories();

    // Категории для section class="tickets-list"
    $category = Category::getCategory($categoryId);

    return $this->render('index', compact('offerCategories', 'dataProvider', 'category', 'countOffers'));
  }
}
