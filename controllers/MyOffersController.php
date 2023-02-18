<?php

namespace app\controllers;

use app\models\Offer;
use app\src\service\OfferService;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class MyOffersController extends Controller
{

  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'denyCallback' => function () {
          return $this->redirect(['/login']);
        },
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index', 'remove'],
            'roles' => ['@'],
          ],
        ]
      ]
    ];
  }

  /**
   * Страница просмотра объявления бъявлений пользователя
   *
   */
  public function actionIndex()
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
   *
   * @throws NotFoundHttpException;
   * @throws ForbiddenHttpException;
   */
  public function actionRemove($offerId)
  {
    $offer = Offer::find()
      ->with('comments')
      ->where(['offer_id' => $offerId])
      ->one();

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    // Если пользователь не обладает правом удаления объявления (не модератор и не автор объявления),
    // то в случае попытки удаления, сервер возвращает код 403 без удаления объявления
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      throw new ForbiddenHttpException();
    }

    OfferService::deleteOffer($offer);

    return $this->redirect(['/my-offers']);
  }
}
