<?php

/** @var yii\web\View $this */

use app\widgets\CategoryWidget;
use app\widgets\NewTicketWidget;
use app\widgets\OfferImageWidget;
use app\widgets\TrimmingStringWidget;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php if (!count($mostTalkedOffers)) : ?>
  <!-- Блок выводится, если нет объявлений.  -->
  <div class="message">
    <div class="message__text">
      <p>На сайте еще не опубликовано ни&nbsp;одного объявления.</p>
    </div>
    <?php if (Yii::$app->user->isGuest) : ?>
      <a href="<?= Url::to('/registration') ?>" class="message__link btn btn--big">Вход и регистрация</a>
    <?php endif; ?>
  </div>
<?php else : ?>
  <!-- Блок выводится, если объявления есть.  -->
  <section class="categories-list">
    <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
    <?= count($offerCategories) ? CategoryWidget::widget(['offerCategories' => $offerCategories, 'contextId' => $this->context->id]) : '' ?>
  </section>
  <section class="tickets-list">
    <h2 class="visually-hidden">Самые новые предложения</h2>
    <div class="tickets-list__wrapper">
      <?= $newOffersdataProvider ? NewTicketWidget::widget(['dataProvider' => $newOffersdataProvider]): '' ?>
    </div>
  </section>
  <section class="tickets-list">
    <h2 class="visually-hidden">Самые обсуждаемые предложения</h2>
    <div class="tickets-list__wrapper">
      <div class="tickets-list__header">
        <p class="tickets-list__title">Самые обсуждаемые</p>
      </div>
      <ul>
        <?php if (count($mostTalkedOffers)) : ?>
        <?php foreach ($mostTalkedOffers as $mostTalkedOffer) : ?>
          <li class="tickets-list__item">
            <div class="ticket-card ticket-card--color09">
              <div class="ticket-card__img">
              <?= OfferImageWidget::widget(['offerImage' => $mostTalkedOffer->offer_image]) ?>
              </div>
              <div class="ticket-card__info">
                <span class="ticket-card__label"><?= isset($mostTalkedOffer->offer_type) ? Html::encode($mostTalkedOffer->offer_type) : '' ?></span>
                <div class="ticket-card__categories">
                  <a href="#">Дом</a>
                </div>
                <div class="ticket-card__header">
                  <h3 class="ticket-card__title"><a href="#"><?= isset($mostTalkedOffer->offer_title) ? Html::encode($mostTalkedOffer->offer_title) : '' ?></a></h3>
                  <p class="ticket-card__price"><span class="js-sum"><?= Html::encode($mostTalkedOffer->offer_price) ?></span> ₽</p>
                </div>
                <div class="ticket-card__desc">
                  <p>
                    <!-- ТЗ:" Анонс, не более 55 символов." -->
                    <?= TrimmingStringWidget::widget(['text' => $mostTalkedOffer->offer_text, 'textLength' => Yii::$app->params['offerTextLength']]) ?>
                  </p>
                </div>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
        <?php endif; ?>
      </ul>
    </div>
  </section>
<?php endif; ?>
