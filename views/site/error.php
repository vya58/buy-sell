<?php

use app\models\forms\OfferSearchForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */

?>

<section class="error">
  <h1 class="error__title"><?= $statusCode ? Html::encode($statusCode) : '' ?></h1>
  <h2 class="error__subtitle"><?= $message ? Html::encode($message) : '' ?></h2>
  <ul class="error__list">
    <?php if (Yii::$app->user->isGuest) : ?>
      <li class="error__item">
        <a href="<?= Url::to('/registration') ?>">Вход и регистрация</a>
      </li>
    <?php endif; ?>
    <?php if (!Yii::$app->user->isGuest) : ?>
      <li class="error__item">
        <a href="<?= Url::to(['/offers/add']) ?>">Новая публикация</a>
      </li>
    <?php endif; ?>
    <li class="error__item">
      <a href="<?= Url::to('/site') ?>">Главная страница</a>
    </li>
  </ul>
  <?php
      $model = new OfferSearchForm();

      if (isset($this->params['query'])) {
        $model->autocompleteForm($model, $this->params['query']);
      }

      $form = ActiveForm::begin([
        'method' => 'get',
        'action' => ['site/search'],
        'options' => [
          'class' => 'error__search search search--small',
          'autocomplete' => 'off',
        ],
      ]); ?>
      <?= $form->field($model, 'search')->input(['placeholder' => 'Поиск'])->label(false) ?>
      <div class="search__icon"></div>
      <div class="search__close-btn"></div>
      <?php ActiveForm::end();
      ?>
      <!--
  <form class="error__search search search--small" method="get" action="#" autocomplete="off">
    <input type="search" name="query" placeholder="Поиск" aria-label="Поиск">
    <div class="search__icon"></div>
    <div class="search__close-btn"></div>
  </form>
    -->
  <a class="error__logo logo" href="<?= Url::to(['site/index']) ?>">
    <img src="/img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
  </a>
</section>
