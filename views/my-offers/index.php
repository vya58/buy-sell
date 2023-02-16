<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\Pjax;
use app\widgets\OfferImageWidget;
use yii\helpers\Url;

?>

<section class="tickets-list">
  <h2 class="visually-hidden">Самые новые предложения</h2>
  <div class="tickets-list__wrapper">
    <div class="tickets-list__header">
      <a href="<?= Url::to(['offers/add']) ?>" class="tickets-list__btn btn btn--big"><span>Новая публикация</span></a>
    </div>
    <ul>
      <?php if (count($offers)) : ?>
        <?php foreach ($offers as $offer) : ?>
          <li class="tickets-list__item js-card">
            <div class="ticket-card ticket-card--color06">
              <div class="ticket-card__img">
              <?= OfferImageWidget::widget(['offerImage' => $offer->offer_image]) ?>
              </div>
              <div class="ticket-card__info">
                <span class="ticket-card__label"><?= isset($offer->offer_type) ? Html::encode($offer->offer_type) : '' ?></span>
                <div class="ticket-card__categories">
                <?php if (isset($offer->categories)) : ?>
                  <?php foreach ($offer->categories as $category) : ?>
                    <a href="#"><?= isset($category->category_name) ? Html::encode($category->category_name) : '' ?></a>
                  <?php endforeach; ?>
                  <?php endif; ?>
                </div>
                <div class="ticket-card__header">
                  <h3 class="ticket-card__title"><a href="<?= isset($offer->offer_id) ? Url::to(['offers/edit', 'id' => $offer->offer_id]) : '#' ?>"><?= isset($offer->offer_title) ? Html::encode($offer->offer_title) : '' ?></a></h3>
                  <p class="ticket-card__price"><span class="js-sum"><?= isset($offer->offer_price) ? Html::encode($offer->offer_price) : '' ?></span> ₽</p>
                </div>
              </div>
              <?php Pjax::begin(); ?>
              <?= isset($offer->offer_id) ? Html::button('Удалить', [
                'class' => 'ticket-card__del js-delete',
                'onclick' => 'window.location.href = "' . Url::to(['/my-offers/remove', 'offerId' => $offer->offer_id]) . '";',
              ]) : '' ?>
              <?php Pjax::end(); ?>
            </div>
          </li>
        <?php endforeach; ?>
      <?php endif; ?>
    </ul>
  </div>
</section>
