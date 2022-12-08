<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Html;
use \yii\helpers\Url;

?>

<section class="error">
  <h1 class="error__title"><?= Html::encode($statusCode) ?></h1>
  <h2 class="error__subtitle"><?= Html::encode($message) ?></h2>
  <ul class="error__list">
    <li class="error__item">
      <a href="<?= Url::to('/registration') ?>">Вход и регистрация</a>
    </li>
    <li class="error__item">
      <a href="new-ticket.html">Новая публикация</a>
    </li>
    <li class="error__item">
      <a href="<?= Url::to('/site') ?>">Главная страница</a>
    </li>
  </ul>
  <form class="error__search search search--small" method="get" action="#" autocomplete="off">
    <input type="search" name="query" placeholder="Поиск" aria-label="Поиск">
    <div class="search__icon"></div>
    <div class="search__close-btn"></div>
  </form>
  <a class="error__logo logo" href="main.html">
    <img src="img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
  </a>
</section>
