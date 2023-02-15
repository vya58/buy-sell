<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\SearchWidget;

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

  <?= SearchWidget::widget(['errorCode' => $statusCode]) ?>

  <a class="error__logo logo" href="<?= Url::to(['site/index']) ?>">
    <img src="/img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
  </a>
</section>
