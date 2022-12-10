<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Comment;
use app\models\Offer;
use app\models\User;

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
    //Временная переменная для подключения статичных вариантов страницы.
    $comments = true;
    //echo 11111;
    // die;
    $offer = Offer::find()
      ->with('owner', 'categories', 'comments')
      ->where(['offer_id' => $id])
      ->one();
    //\yii\helpers\VarDumper::dump($offer->comments, 3, true);
    //var_dump($offer->offer_date_create);

    $categories = $offer->categories;
    $owner = $offer->owner;

    $comments = $offer->comments;
    /*
    $comments1 = Comment::find()
    ->leftJoin(User::tableName() . ' u', 'u.user_id = owner_id')
      //->with('owner')
      ->where(['offer_id' => $id])
      ->all();
      \yii\helpers\VarDumper::dump($comments1, 3, true);
      */
    if (!$offer) {
      throw new NotFoundHttpException();
    }

    return $this->render(
      'index',
      [
        'offer' => $offer,
        'owner' => $owner,
        //'dataProvider' => $dataProvider,
        'categories' => $categories,
        'comments' => $comments,
      ]
    );
  }
}
