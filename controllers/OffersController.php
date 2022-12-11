<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Offer;

class OffersController extends Controller
{
  /**
   * Страница просмотра объявления
   *
   * @param int $id - id объявления
   * @return Response|string - код страницы просмотра объявления
   * @throws NotFoundHttpException
   */
  public function actionIndex(int $id): Response|string
  {
    $offer = Offer::find()
      ->with('owner', 'categories', 'comments')
      ->where(['offer_id' => $id])
      ->one();

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    $owner = $offer->owner;
    $categories = $offer->categories;
    $comments = $offer->comments;

    return $this->render(
      'index',
      [
        'offer' => $offer,
        'owner' => $owner,
        'categories' => $categories,
        'comments' => $comments,
      ]
    );
  }
}
