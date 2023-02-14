<?php

namespace app\controllers;

use app\models\Offer;
use app\models\OfferCategory;
use app\models\forms\OfferSearchForm;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
  /**
   * Displays homepage.
   *
   * @return Response|string
   */
  public function actionIndex(): Response|string
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
   * @return Response|string
   */

  public function actionLogout(): Response|string
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }

  public function actionError()
  {
    $this->layout = 'error';
    $this->view->params['htmlClass'] = 'html-not-found';
    $this->view->params['bodyClass'] = 'body-not-found';
    $exception = Yii::$app->errorHandler->exception;
    $statusCode = $exception->statusCode;
    $message = 'Страница не найдена';

    if ($exception->statusCode >= 500) {
      $this->view->params['htmlClass'] = 'html-server';
      $this->view->params['bodyClass'] = 'body-server';
      $message = 'Ошибка cервера';

      return $this->render('error', compact('statusCode', 'message'));
    }
    return $this->render('error', compact('statusCode', 'message'));
  }

  /**
   * Страница результатов поиска объявлений
   *
   * @return Response|string
   */
  public function actionSearch(): Response|string
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
