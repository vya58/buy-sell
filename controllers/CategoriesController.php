<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Category;
use app\models\Offer;
use app\models\OfferCategory;
use yii\data\ActiveDataProvider;

class CategoriesController extends Controller
{
  /**
   * Страница просмотра объявлений соответствующей категории
   *
   * @param int $id - id категории
   * @return Response|string - код страницы
   * @throws NotFoundHttpException
   */
  public function actionIndex(int $id): Response|string
  {
    $query = Offer::getCategoryOffers($id);

    $countOffers = $query->count();

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
    $category = Category::getCategory($id);

    return $this->render('index', compact('offerCategories', 'dataProvider', 'category', 'countOffers'));
  }
}
