<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use \yii\helpers\Url;
use app\models\Offer;
use app\models\User;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;

?>

<section class="tickets-list">
  <h2 class="visually-hidden">Самые новые предложения</h2>
  <div class="tickets-list__wrapper">
    <div class="tickets-list__header">
      <a href="<?= Url::to(['offers/add']) ?>" class="tickets-list__btn btn btn--big"><span>Новая публикация</span></a>
    </div>
    <ul>
      <?php foreach ($offers as $offer) : ?>
        <li class="tickets-list__item js-card">
          <div class="ticket-card ticket-card--color06">
            <div class="ticket-card__img">
              <img src="<?= $offer->offer_image ? Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $offer->offer_image) : Html::encode('../img/blank.png') ?>" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label"><?= Html::encode($offer->offer_type) ?></span>
              <div class="ticket-card__categories">
              <?php foreach ($offer->categories as $category) : ?>
                    <a href="#"><?= Html::encode($category->category_name) ?></a>
                  <?php endforeach; ?>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="<?= Url::to(['offers/edit', 'id' => $offer->offer_id]) ?>"><?= Html::encode($offer->offer_title) ?></a></h3>
                <p class="ticket-card__price"><span class="js-sum"><?= Html::encode($offer->offer_price) ?></span> ₽</p>
              </div>
            </div>
            <?php Pjax::begin(); ?>
                <?= Html::button('Удалить', [
                  'class' => 'ticket-card__del js-delete',
                  'onclick' => 'window.location.href = "' . Url::to(['/my-offers/remove', 'offerId' => $offer->offer_id]) . '";',
                ]); ?>
                <?php Pjax::end(); ?>
                <!--
            <button class="ticket-card__del js-delete" type="button">Удалить</button>
              -->
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
