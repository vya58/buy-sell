<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Firebase;
use Kreait\Firebase\Factory;
use app\models\Offer;
use app\models\forms\OfferAddForm;
use app\models\forms\CommentAddForm;
use yii\helpers\ArrayHelper;

class OffersController extends Controller
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

    ArrayHelper::multisort($comments, ['comment_id'], [SORT_DESC]);

    $commentAddForm = null;

    // Добавление нового комментария. Доступно только зарегистрированным пользователям.
    if (!Yii::$app->user->isGuest) {
      $commentAddForm = new CommentAddForm();

      if (Yii::$app->request->getIsPost()) {
        $commentAddForm->load(Yii::$app->request->post());

        if ($commentAddForm->addComment($id)) {
          return $this->redirect(['offers/index', 'id' => $id]);
        }
      }
    }

    return $this->render('index', compact('offer', 'owner', 'categories', 'comments', 'commentAddForm'));
  }

  /**
   * Страница с формой добавления объявления
   *
   * @return string - код страницы с формой создания задания
   */
  public function actionAdd()
  {
    $ticketFormTitle = 'Новая публикация';

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
    return $this->render('add', compact('offerAddForm', 'ticketFormTitle'));
  }

  /**
   * Страница с формой редактирования объявления
   *
   * @param int $id - id объявления
   */
  public function actionEdit($id)
  {
    $offer = Offer::find()
      ->with('owner')
      ->where(['offer_id' => $id])
      ->one();

    // Если пользователь не обладает правом редактирования объявления (не модератор и не автор объявления),
    // то он переадресуется на страницу просмотра объявления
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      return $this->redirect(['offers/index', 'id' => $id]);
    }

    $ticketFormTitle = 'Редактировать публикацию';

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    $offerAddForm = new OfferAddForm();
    $offerAddForm->autocompleteForm($offerAddForm, $offer);

    if (Yii::$app->request->getIsPost()) {
      $offerAddForm->load(Yii::$app->request->post());
      $offerId = $offerAddForm->addOffer($id);

      if ($offerId) {
        return $this->redirect(['offers/index', 'id' => $offerId]);
      }
    }
    return $this->render('add', compact('offerAddForm', 'ticketFormTitle'));
  }

  public function actionSend()
  {
    $factory = (new Factory)
      ->withServiceAccount('/OpenServ/domains/config/buysellchat-c6e28-firebase-adminsdk-4k4m2-1c314d0e34.json')
      ->withDatabaseUri(Yii::$app->params['firebase_database_uri']);
    $database = $factory->createDatabase();
    //$firebase = new Firebase();
    \yii\helpers\VarDumper::dump($factory, 3, true);
  }
}
