<?php

/** @var yii\web\View $this */

use app\assets\FirebaseAsset;
use app\models\User;
use app\widgets\CategoryWidget;
use app\widgets\TimeFormattWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use app\widgets\OfferImageWidget;

FirebaseAsset::register($this);

?>

<section class="ticket">
  <div class="ticket__wrapper">
    <h1 class="visually-hidden">Карточка объявления</h1>
    <div class="ticket__content">
      <div class="ticket__img">
        <?= OfferImageWidget::widget(['offerImage' => $offer->offer_image]) ?>
      </div>
      <div class="ticket__info">
        <h2 class="ticket__title" data-attr="<?= isset($offer->offer_id) ? Html::encode($offer->offer_id) : '' ?>"><?= isset($offer->offer_title) ? Html::encode($offer->offer_title) : '' ?></h2>
        <div class="ticket__header">
          <p class="ticket__price"><span class="js-sum"><?= isset($offer->offer_price) ? Html::encode($offer->offer_price) : '' ?></span> ₽</p>
          <p class="ticket__action"><?= isset($offer->offer_type) ? Html::encode($offer->offer_type) : '' ?></p>
        </div>
        <div class="ticket__desc">
          <p><?= isset($offer->offer_text) ? Html::encode($offer->offer_text) : '' ?></p>
        </div>
        <div class="ticket__data">
          <p>
            <b>Дата добавления:</b>
            <span><?= isset($offer->offer_date_create) ? Html::encode(Yii::$app->formatter->asDate($offer->offer_date_create, 'php:j F Y')) : '' ?></span>
          </p>
          <p>
            <b>Автор:</b>
            <a href="<?= isset($owner->user_id) ? Url::to(['user/index', 'id' => $owner->user_id]) : '#' ?>"><?= isset($owner->name) ? Html::encode($owner->name) : '' ?></a>
          </p>
          <p>
            <b>Контакты:</b>
            <a href="mailto:<?= isset($owner->email) ? Html::encode($owner->email) : '' ?>"><?= isset($owner->email) ? Html::encode($owner->email) : '' ?></a>
          </p>
        </div>
        <?= (count($offerCategories) && $this->context->id) ? CategoryWidget::widget(['offerCategories' => $offerCategories, 'contextId' => $this->context->id]) : '' ?>
      </div>
    </div>
    <div class="ticket__comments">
      <div class="ticket__warning">
        <?php if (Yii::$app->user->isGuest) : ?>
          <p>Отправка комментариев доступна <br>только для зарегистрированных пользователей.</p>
          <a href="<?= Url::to(['/registration']) ?>" class="message__link btn btn--big">Вход и регистрация</a>
        <?php endif; ?>
      </div>
      <h2 class="ticket__subtitle">Коментарии</h2>
      <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="ticket__comment-form">
          <?php $form = ActiveForm::begin([
            'id' => 'comment-add-form',
            'method' => 'post',
            'options' => [
              'class' => 'form comment-form',
            ]
          ]); ?>
          <div class="comment-form__header">
            <a href="#" class="comment-form__avatar avatar">
              <img src="<?= isset(Yii::$app->user->identity->avatar) ? Html::encode(User::USER_AVATAR_UPLOAD_PATH . Yii::$app->user->identity->avatar) : '/img/avatar.jpg' ?>" srcset="<?= isset(Yii::$app->user->identity->avatar) ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
            </a>
            <p class="comment-form__author">Вам слово</p>
          </div>
          <div class="comment-form__field">
            <div class="form__field">
              <?= $form->field($commentAddForm, 'commentText')->textarea(['cols' => 30, 'rows' => 10, 'options' => ['class' => 'js-field']])->label('Текст комментария') ?>
              <span>Обязательное поле</span>
            </div>
          </div>
          <button class="comment-form__button btn btn--white js-button" type="submit">Отправить</button>
          <?php ActiveForm::end(); ?>
        </div>
      <?php endif; ?>
      <?php if (count($comments)) : ?>
        <div class="ticket__comments-list">
          <ul class="comments-list">
            <?php foreach ($comments as $comment) : ?>
              <?php
              if (isset($comment->owner_id)) {
                $user = User::findOne($comment->owner_id);
              }
              ?>
              <li>
                <div class="comment-card">
                  <div class="comment-card__header">
                    <a href="#" class="comment-card__avatar avatar">
                      <img src="<?= isset($user->avatar) ? Html::encode(User::USER_AVATAR_UPLOAD_PATH . $user->avatar) : '/img/avatar.jpg' ?>" srcset="<?= isset($user->avatar) ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
                    </a>
                    <p class="comment-card__author"><?= isset($user->name) ? Html::encode($user->name) : '' ?></p>
                  </div>
                  <div class="comment-card__content">
                    <?= isset($comment->comment_text) ? Html::tag('p', Html::encode($comment->comment_text), ['style' => ['word-wrap' => 'break-word']]) : Html::tag('p', '', ['style' => ['word-wrap' => 'break-word']]) ?>
                  </div>
                </div>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php else : ?>
        <div class="ticket__message">
          <p>У этой публикации еще нет ни одного комментария.</p>
        </div>
      <?php endif; ?>
    </div>
    <?php if (!Yii::$app->user->isGuest) : ?>
      <?php if ($dataProvider) : ?>
        <?= ListView::widget([
          'dataProvider' => $dataProvider,
          'itemView' => '_chat',
          'layout' => "{items}\n{pager}",
          'emptyText' => false,
          'viewParams' => [
            'offer' => $offer,
            'dataProvider' => $dataProvider,
          ],
          'pager' => [
            'prevPageLabel' => '&lt',
            'nextPageLabel' => '&gt',
            'maxButtonCount' => 0,
            'disableCurrentPageButton' => true,
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'active'],
            'options' => [
              'tag' => 'div',
              'class' => 'buyer-pagination',
            ],
          ],
        ])
        ?>
      <?php endif; ?>
      <button class="chat-button" type="button" aria-label="Открыть окно чата" <?= !$chat ? 'disabled' : '' ?>></button>
    <?php endif; ?>
  </div>
</section>

<?php if (!Yii::$app->user->isGuest && $chat) : ?>
  <section class="chat visually-hidden">
    <?php
    $addresseeName = '';
    if (isset($addressee->name)) {
      $addresseeName = $addressee->name;
    };
    $addresseeId = null;
    if (isset($addressee->user_id)) {
      $addresseeId = $addressee->user_id;
    };
    ?>
    <?php if (isset($owner->user_id) && Yii::$app->user->id === $owner->user_id) : ?>
      <h2 class="chat__subtitle" data-receiver-id="<?= Html::encode($addresseeId) ?>" data-receiver-name="<?= Html::encode($addresseeName) ?>">
        Чат с покупателем
        <?= ($addresseeId === $chat->getBuyerId())  ? '' : Html::encode($addresseeName) ?>
      </h2>
    <?php else : ?>
      <h2 class="chat__subtitle" data-receiver-id="<?= Html::encode($addresseeId) ?>" data-receiver-name="<?= Html::encode($addresseeName) ?>">Чат с продавцом <?= Html::encode($addresseeName) ?></h2>
    <?php endif; ?>
    <ul id="chat__conversation" class="chat__conversation" data-buyer-id="<?= Html::encode($chat->getBuyerId()) ?>">
      <?php if (isset($messages)) : ?>
        <?php foreach ($messages as $key => $message) : ?>
          <!-- Подсветка непрочитанных сообщений class="unread" -->
          <li class="chat__message <?= !isset($message['read']) ? 'unread' : '' ?>">
            <div class="chat__message-title">
              <?php if (isset($message['fromUserId'])) : ?>
                <span class="chat__message-author"><?= $message['fromUserId'] !== $addresseeId ? 'Вы' : Html::encode($addresseeName) ?></span>
              <?php endif; ?>
              <?php if (isset($message['date'])) : ?>
                <?= TimeFormattWidget::widget(['date' => $message['date']]) ?>
              <?php endif; ?>
            </div>
            <div class="chat__message-content">
              <p><?= isset($message['message']) ? Html::encode($message['message']) : '' ?></p>
            </div>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
    <?php Pjax::begin([]); ?>
    <?php $formChat = ActiveForm::begin([
      'id' => 'chat-form',
      'method' => 'pjax',
      'enableAjaxValidation' => false,
      'options' => [
        'class' => 'chat__form',
        'data-pjax' => true,
      ]
    ]); ?>
    <?= $formChat->field($chatForm, 'message', ['options' => ['tag' => false], 'inputOptions' => ['class' => 'chat__form-message']])->textarea(['placeholder' => "Ваше сообщение в чат"])->label(false) ?>
    <button class="chat__form-button" type="submit" aria-label="Отправить сообщение в чат"></button>
    <?php ActiveForm::end(); ?>
    <?php Pjax::end(); ?>
  </section>

<?php endif; ?>

<template id="chat__message">
  <li class="chat__message">
    <div class="chat__message-title">
      <span class="chat__message-author">Вы</span>
      <time class="chat__message-time" datetime="2021-11-18T21:15">21:15</time>
    </div>
    <div class="chat__message-content">
      <p>Добрый день!</p>
      <p>Какова ширина кресла? Из какого оно материала?</p>
    </div>
  </li>
</template>
