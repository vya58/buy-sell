<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\ChatFirebase;
use app\models\FireDatabase;
//use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use app\models\Offer;
use app\models\forms\OfferAddForm;
use app\models\forms\ChatForm;
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
    //$answer = 'Нет isPjax';
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

    $commentAddForm = new CommentAddForm();

    // Добавление нового комментария. Доступно только зарегистрированным пользователям.
    if (!Yii::$app->user->isGuest && $commentAddForm->load(Yii::$app->request->post())) {

      if ($commentAddForm->addComment($id)) {
        return $this->redirect(['offers/index', 'id' => $id]);
      }
    }

    $chatForm = new ChatForm();

    if (Yii::$app->user->id !== $owner->user_id) {
      $buyerId = Yii::$app->user->id;
    }

    $chatFirebase = new ChatFirebase($id, $buyerId);
    // Добавление нового cсообщения в чат. Доступно только зарегистрированным пользователям.
    if (!Yii::$app->user->isGuest && $chatForm->load(Yii::$app->request->post())) {
      //\yii\helpers\VarDumper::dump($chatFirebase, 3, true);
      $messages = $chatFirebase->getValueChat();
      $chatForm->addMessage($chatFirebase);
      return $this->redirect('/chat/index');
    }

    return $this->render('index', compact('offer', 'owner', 'categories', 'comments', 'commentAddForm', 'chatForm'));
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
}
