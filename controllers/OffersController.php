<?php

namespace app\controllers;

use app\src\Chat;
use app\models\Offer;
use app\models\forms\ChatForm;
use app\models\forms\CommentAddForm;
use app\models\forms\OfferAddForm;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class OffersController extends Controller
{
  /**
   * Страница просмотра объявления
   *
   * @param int $id - id объявления
   * @param int|null $buyerId - id покупателя, null - если страница продавца
   * @param int|null $currentPage - номер текущей страницы пагинатора для провайдера данных чата продавца
   *
   * @throws NotFoundHttpException
   */
  public function actionIndex(int $id, int $buyerId = null, int $currentPage = null)
  {
    $offer = Offer::find()
      ->with('owner', 'categories', 'offerCategories', 'comments')
      ->where(['offer_id' => $id])
      ->one();

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    $owner = $offer->owner;
    $categories = $offer->categories;
    $offerCategories = $offer->offerCategories;
    $comments = $offer->comments;

    ArrayHelper::multisort($comments, ['comment_id'], [SORT_DESC]);

    $commentAddForm = new CommentAddForm();

    // Добавление нового комментария. Доступно только зарегистрированным пользователям.
    if (!Yii::$app->user->isGuest && $commentAddForm->load(Yii::$app->request->post()) && $commentAddForm->addComment($id)) {
      return $this->redirect(['/offers', 'id' => $id]);
    }

    $dataProvider = Chat::getDataProviderForChat($offer, $currentPage);

    // Если пользователь - не владелец объявления, значит он покупатель
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      $buyerId = \Yii::$app->user->id;
    }

    $chatForm = new ChatForm();
    $chat = null;
    $messages = null;
    $addressee = null;

    // Если есть покупатель, создаётся чат
    if ($buyerId) {
      // Если страница владельца объявления, то адресат сообщения - покупатель с id = $buyerId
      $chat = new Chat($id, $buyerId);
      $addressee = $chat->getAddresse($owner);

      // Выборка всех сообщений покупателя с id = $buyerId объявления с данным id
      $messages = $chat->getBuyerChat();
    }

    // Добавление нового cообщения в чат. Доступно только зарегистрированным пользователям при наличии заполненного поля ввода сообщения.
    if ($chat && $chatForm->load(Yii::$app->request->post()) && !Yii::$app->user->isGuest && Yii::$app->request->isAjax) {
      $messages = $chat->sendMessage($addressee, $chatForm);

      // Обнуляем форму чата
      $chatForm = new ChatForm();
    }

    return $this->render('index', compact('offer', 'offerCategories', 'owner', 'categories', 'comments', 'commentAddForm', 'chatForm', 'messages', 'addressee', 'dataProvider', 'chat'));
  }

  /**
   * Страница с формой добавления объявления
   *
   */
  public function actionAdd()
  {
    if (Yii::$app->user->isGuest) {
      return $this->redirect(['/login']);
    }

    $ticketFormTitle = 'Новая публикация';

    $offerAddForm = new OfferAddForm();

    if (Yii::$app->request->getIsPost() && $offerAddForm->load(Yii::$app->request->post()) && $offerAddForm->addOffer()) {
      return $this->redirect(['/my-offers']);
    }
    return $this->render('add', compact('offerAddForm', 'ticketFormTitle'));
  }

  /**
   * Страница с формой редактирования объявления
   *
   * @param int $id - id объявления
   *
   * @throws NotFoundHttpException
   */
  public function actionEdit($id)
  {
    $offer = Offer::find()
      ->with('owner')
      ->where(['offer_id' => $id])
      ->one();

    if (!$offer) {
      throw new NotFoundHttpException();
    }

    if (Yii::$app->user->isGuest) {
      return $this->redirect(['/login']);
    }

    // Если пользователь не обладает правом редактирования объявления (не модератор и не автор объявления),
    // то он переадресуется на страницу просмотра объявления
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
      return $this->redirect(['offers/', 'id' => $id]);
    }

    $ticketFormTitle = 'Редактировать публикацию';

    $offerAddForm = new OfferAddForm();
    $offerAddForm->autocompleteForm($offerAddForm, $offer);

    if (Yii::$app->request->getIsPost() && $offerAddForm->load(Yii::$app->request->post())) {
      $offerId = $offerAddForm->addOffer($id);

      if ($offerId) {
        return $this->redirect(['offers/', 'id' => $offerId]);
      }
    }
    return $this->render('add', compact('offerAddForm', 'ticketFormTitle'));
  }
}
