<?php

namespace app\controllers;

use app\models\Offer;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class MyOffersController extends Controller
{
  /**
   * Страница просмотра объявления бъявлений пользователя
   *
   * @return Response|string - код страницы
   */
  public function actionIndex(): Response|string
  {
    $offers = Offer::find()
      ->with('categories')
      ->where(['owner_id' => Yii::$app->user->id])
      ->orderBy(['offer_date_create' => SORT_DESC])
      ->all();

    return $this->render('index', compact('offers'));
  }

  /**
   * Удаление объявления пользователя
   *
   * @param int $offerId - id объявления
   * @return Response|string - код страницы просмотра страницы комментариев
   */
  public function actionRemove($offerId): Response|string
  {
    $offer = Offer::find()
      ->with('comments')
      ->where(['offer_id' => $offerId])
      ->one();

    // Если пользователь не обладает правом удаления объявления (не модератор и не автор объявления),
    // то он переадресуется на страницу просмотра объявления без удаления комментария
    if (\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {

      $offer->deleteOffer($offer);
    }
    return $this->redirect(['my-offers/index']);
  }
}
