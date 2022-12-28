<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Offer;
use app\models\forms\OfferAddForm;
use app\models\forms\CommentAddForm;
use yii\helpers\ArrayHelper;

class MyOffersController extends Controller
{
  /**
   * {@inheritdoc}
   */
  /*
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'only' => ['edit'],
        'rules' => [
          [
            'actions' => ['index'],
            'allow' => true,
            'roles' => ['*'],
          ],
          [
            'actions' => ['edit'],
            'allow' => true,
            'roles' => ['updateOwnContent'],
          ],
        ],
      ],

      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }
*/
  /**
   * Страница просмотра объявления бъявлений пользователя
   *
   * @return Response|string - код страницы
   * @throws NotFoundHttpException
   */
  public function actionIndex(): Response|string
  {
    $offers = Offer::find()
      ->with('categories')
      ->where(['owner_id' => Yii::$app->user->id])
      ->orderBy(['offer_date_create' => SORT_DESC])
      ->all();

    //\yii\helpers\VarDumper::dump($offer, 3, true);
    //die;


   // $owner = $offer->owner;
   // $categories = $offer->categories;
    //$comments = $offer->comments;

   // ArrayHelper::multisort($comments, ['comment_id'], [SORT_DESC]);


    return $this->render(
      'index',
      [
        'offers' => $offers,
        //'owner' => $owner,
        //'categories' => $categories,
        //'comments' => $comments,

      ]
    );
  }

  /**
   * Удаление объявления пользователя
   *
   * @param int $offerId - id объявления
   * @return Response|string - код страницы просмотра страницы комментариев
   * @throws NotFoundHttpException
   */
  public function actionRemove($offerId): Response|string
  {
    $offer = Offer::find()
      ->with('comments')
      ->where(['offer_id' => $offerId])
      ->one();

    // Если пользователь не обладает правом удаления объявления (не модератор и не автор объявления),
    // то он переадресуется на страницу просмотра объявления без удаления комментария
    if (\Yii::$app->user->can('updateOwnContent', ['resource' => $offer]) ) {

       $offer->deleteOffer($offer);
    }
    return $this->redirect(['my-offers/index']);
  }
}
