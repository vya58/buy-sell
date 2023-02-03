<?php

/** @var yii\web\View $this */

use app\models\Offer;
use app\widgets\NewTicketWidget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<?php if (!$mostTalkedOffers) : ?>
  <!-- Блок выводится, если нет объявлений.  -->
  <div class="message">
    <div class="message__text">
      <p>На сайте еще не опубликовано ни&nbsp;одного объявления.</p>
    </div>
    <?php if (Yii::$app->user->isGuest) : ?>
      <a href="<?= Url::to('/registration/index') ?>" class="message__link btn btn--big">Вход и регистрация</a>
    <?php endif; ?>
  </div>
<?php else : ?>
  <!-- Блок выводится, если объявления есть.  -->
  <section class="categories-list">
    <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
    <ul class="categories-list__wrapper">
      <?php $categoryIds = []; ?>
      <?php foreach ($offerCategories as $offerCategory) : ?>
        <?php if (!ArrayHelper::isIn($offerCategory->category->category_id, $categoryIds)) : ?>
          <?php $countOffersInCategory = $offerCategory->getCountOffersInCategory($offerCategory->category->category_id); ?>
          <li class="categories-list__item">
            <a href="<?= Url::to(['categories/index', 'id' => $offerCategory->category->category_id]) ?>" class="category-tile category-tile--default">
              <span class="category-tile__image">
                <img src="<?= Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . Offer::getImageOfRandomOffers($offerCategory)) ?>" alt="Иконка категории">
              </span>
              <span class="category-tile__label"><?= Html::encode($offerCategory->category->category_name) ?> <span class="category-tile__qty js-qty"><?= Html::encode($countOffersInCategory) ?></span></span>
            </a>
          </li>
        <?php endif; ?>
        <?php $categoryIds[] = $offerCategory->category->category_id; ?>
      <?php endforeach; ?>
    </ul>
  </section>
  <section class="tickets-list">
    <h2 class="visually-hidden">Самые новые предложения</h2>
    <div class="tickets-list__wrapper">
      <?= NewTicketWidget::widget(['dataProvider' => $newOffersdataProvider]) ?>
    </div>
  </section>
  <section class="tickets-list">
    <h2 class="visually-hidden">Самые обсуждаемые предложения</h2>
    <div class="tickets-list__wrapper">
      <div class="tickets-list__header">
        <p class="tickets-list__title">Самые обсуждаемые</p>
      </div>
      <ul>
        <?php foreach ($mostTalkedOffers as $mostTalkedOffer) : ?>
          <li class="tickets-list__item">
            <div class="ticket-card ticket-card--color09">
              <div class="ticket-card__img">
                <img src="<?= $mostTalkedOffer->offer_image ? Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $mostTalkedOffer->offer_image) : Html::encode('../img/blank.png') ?>" alt="Изображение товара">
              </div>
              <div class="ticket-card__info">
                <span class="ticket-card__label"><?= Html::encode($mostTalkedOffer->offer_type) ?></span>
                <div class="ticket-card__categories">
                  <a href="#">Дом</a>
                </div>
                <div class="ticket-card__header">
                  <h3 class="ticket-card__title"><a href="#"><?= Html::encode($mostTalkedOffer->offer_title) ?></a></h3>
                  <p class="ticket-card__price"><span class="js-sum"><?= Html::encode($mostTalkedOffer->offer_price) ?></span> ₽</p>
                </div>
                <div class="ticket-card__desc">
                  <p>
                    <!-- ТЗ:" Анонс, не более 55 символов." -->
                    <?php if (mb_strlen($mostTalkedOffer->offer_text) > Yii::$app->params['offerTextLength']) : ?>
                      <?= Html::encode(mb_substr($mostTalkedOffer->offer_text, 0, Yii::$app->params['offerTextLength']) . '...') ?>
                    <?php else : ?>
                      <?= Html::encode($mostTalkedOffer->offer_text) ?>
                    <?php endif; ?>
                  </p>
                </div>
              </div>
            </div>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
  </section>
<?php endif; ?>
