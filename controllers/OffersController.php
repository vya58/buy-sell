<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Offer;
use app\models\forms\OfferAddForm;

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

  /**
   * Страница с формой добавления объявления
   *
   * @return string - код страницы с формой создания задания
   */
  public function actionAdd()
  {
    if (Yii::$app->user->isGuest) {
      return $this->goHome();
    }

    $offerAddForm = new OfferAddForm();

    if (Yii::$app->request->getIsPost()) {
      $offerAddForm->load(Yii::$app->request->post());
      $offerId = $offerAddForm->addOffer();

      if ($offerId) {
        return $this->redirect(['offers/index', 'id' => $offerId]);
      }
    }
    return $this->render('add', ['offerAddForm' => $offerAddForm]);
  }
}
