<?php

namespace app\controllers;

use app\models\Offer;
use app\models\OfferCategory;
use app\models\forms\OfferSearchForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class SiteController extends Controller
{
  /**
   * Displays homepage.
   *
   */
  public function actionIndex()
  {
    // Категории для section class="categories-list"
    $offerCategories = OfferCategory::find()
      ->with('category')
      ->all();

    // Самые новые предложения
    $newOffersdataProvider = new ActiveDataProvider([
      'query' => Offer::getNewOffers(),
      'pagination' => [
        'pageSize' => Yii::$app->params['newOffersCount'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);
    // Самые обсуждаемые предложения
    $mostTalkedOffers = Offer::getMostTalkedOffers();

    return $this->render('index', compact('offerCategories', 'newOffersdataProvider', 'mostTalkedOffers'));
  }

  /**
   * Logout action.
   *
   */

  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }

  public function actionError()
  {
    $exception = Yii::$app->errorHandler->exception;
    $statusCode = $exception->statusCode;
    $message = 'Страница не найдена';
    $this->layout = 'error';
    $this->view->params['statusCode'] = $statusCode;

    if ($exception->statusCode >= 500) {
      $message = 'Ошибка cервера';

      return $this->render('error', compact('statusCode', 'message'));
    }
    return $this->render('error', compact('statusCode', 'message'));
  }

  /**
   * Страница результатов поиска объявлений
   *
   */
  public function actionSearch()
  {
    $foundOffers = null;
    $model = new OfferSearchForm();

    if (Yii::$app->request->getIsGet()) {
      $search = Yii::$app->request->get();
      $model->load($search);
      $query = $model->search;
      $this->view->params['query'] = $query;
      $foundOffers = Offer::searchOffers($query);
    }

    $dataProvider = new ActiveDataProvider([
      'query' => $foundOffers,
      'pagination' => [
        'pageParam' => 'page-search',
        'pageSize' => Yii::$app->params['pageSize'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);

    // Самые новые предложения
    $newOffersdataProvider = new ActiveDataProvider([
      'query' => Offer::getNewOffers(),
      'pagination' => [
        'pageParam' => 'page-new',
        'pageSize' => Yii::$app->params['newOffersCount'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);

    return $this->render('search', compact('newOffersdataProvider', 'dataProvider'));
  }
}
