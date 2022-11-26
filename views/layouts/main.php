<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;

AppAsset::register($this);

$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/img/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">

<head>
  <title>Куплю Продам</title>
  <?php $this->head() ?>
</head>

<body>
  <?php $this->beginBody() ?>

  <header class="header">
    <div class="header__wrapper">
      <a class="header__logo logo" href="main.html">
        <img src="img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
      </a>
      <nav class="header__user-menu">
        <ul class="header__list">
          <li class="header__item">
            <a href="#">Публикации</a>
          </li>
          <li class="header__item">
            <a href="#">Комментарии</a>
          </li>
        </ul>
      </nav>
      <form class="search" method="get" action="#" autocomplete="off">
        <input type="search" name="query" placeholder="Поиск" aria-label="Поиск">
        <div class="search__icon"></div>
        <div class="search__close-btn"></div>
      </form>
      <a class="header__avatar avatar" href="#">
        <img src="img/avatar.jpg" srcset="img/avatar@2x.jpg 2x" alt="Аватар пользователя">
      </a>
      <a class="header__input" href="sign-up.html">Вход и регистрация</a>
    </div>
  </header>

  <main class="page-content">
    <?= $content ?>
  </main>

  <footer class="page-footer">
    <div class="page-footer__wrapper">
      <div class="page-footer__col">
        <a href="#" class="page-footer__logo-academy" aria-label="Ссылка на сайт HTML-Академии">
          <svg width="132" height="46">
            <use xlink:href="img/sprite_auto.svg#logo-htmlac"></use>
          </svg>
        </a>
        <p class="page-footer__copyright">© 2019 Проект Академии</p>
      </div>
      <div class="page-footer__col">
        <a href="#" class="page-footer__logo logo">
          <img src="img/logo.svg" width="179" height="35" alt="Логотип Куплю Продам">
        </a>
      </div>
      <div class="page-footer__col">
        <ul class="page-footer__nav">
          <li>
            <a href="sign-up.html">Вход и регистрация</a>
          </li>
          <li>
            <a href="new-ticket.html">Создать объявление</a>
          </li>
        </ul>
      </div>
    </div>
  </footer>

  <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>
