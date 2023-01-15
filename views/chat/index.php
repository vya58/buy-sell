<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use \yii\helpers\Url;
use app\models\Category;
use app\models\Offer;
use app\models\User;
use yii\widgets\ActiveForm;
use app\assets\FirebaseAsset;
use yii\widgets\Pjax;

FirebaseAsset::register($this);
?>

<section class="chat">
  <!--<section class="chat visually-hidden">-->
  <h2 class="chat__subtitle">Чат с продавцом</h2>
  <ul class="chat__conversation">
    <?php foreach ($messages as $message) : ?>
      <li class="chat__message">
        <div class="chat__message-title">
          <span class="chat__message-author"><?= $message['userId'] === Yii::$app->user->id ? 'Вы' : Html::encode($buyerName) ?></span>
          <time class="chat__message-time" datetime="2021-11-18T21:15"><?= Yii::$app->formatter->asDate($message['date'], 'php:j F Y') === Yii::$app->formatter->asDate('now', 'php:j F Y') ? Html::encode(Yii::$app->formatter->asDate($message['date'], 'php:H:i')) : Html::encode(Yii::$app->formatter->asDate($message['date'], 'php:j F Y H:i')) ?></time>
        </div>
        <div class="chat__message-content">
          <p><?= Html::encode($message['message']) ?></p>
        </div>
      </li>
    <?php endforeach; ?>
  </ul>
  <?php Pjax::begin([
    //'id' => 'chat-form',
    'timeout' => 4000,
  ]); ?>
  <?php $formChat = ActiveForm::begin([
    'id' => 'chat-form',
    //'method' => 'pjax',
    //'validateOnSubmit' => false,
    //'action' => '/offers/index/' . $offer->offer_id,
    'options' => [
      'class' => 'chat__form',
      //'data-pjax' => true,
    ]
  ]); ?>
  <!--<form class="chat__form" action="/offers/send" method="post">-->
  <label class="visually-hidden" for="chat-field">Ваше сообщение в чат</label>
  <?= $formChat->field($chatForm, 'message')->textarea(['options' => ['class' => 'chat__form-message']]) ?>
  <!--<textarea class="chat__form-message" name="chat-message" id="chat-field" placeholder="Ваше сообщение в чат"></textarea>-->

  <?php /* Html::submitButton('', [
    'class' => 'chat__form-button',
  ]);*/ ?>

  <button class="chat__form-button" type="submit" aria-label="Отправить сообщение в чат"></button>
  <!--</form>-->
  <?php ActiveForm::end(); ?>
  <?php Pjax::end(); ?>

</section>
