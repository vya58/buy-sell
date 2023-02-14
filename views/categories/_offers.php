<?php

use app\models\Offer;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="ticket-card ticket-card--color06">
  <div class="ticket-card__img">
    <img src="<?= isset($model->offer_image) ? Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $model->offer_image) : Html::encode('/img/blank.png') ?>" alt="Изображение товара">
  </div>
  <div class="ticket-card__info">
    <span class="ticket-card__label"><?= isset($model->offer_type) ? Html::encode($model->offer_type) : '' ?></span>
    <div class="ticket-card__categories">
      <?php if (isset($model->categories)) : ?>
        <?php foreach ($model->categories as $category) : ?>
          <a href="<?= isset($category['category_id']) ? Url::to(['categories/index', 'id' => $category['category_id']]) : '#' ?>"><?= isset($category['category_name']) ? Html::encode($category['category_name']) : '' ?></a>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>
    <div class="ticket-card__header">
      <h3 class="ticket-card__title"><a href="<?= isset($model->offer_id) ? Url::to(['offers/index', 'id' => $model->offer_id]) : '#' ?>"><?= isset($model->offer_title) ? Html::encode($model->offer_title) : '' ?></a></h3>
      <p class="ticket-card__price"><span class="js-sum"><?= isset($model->offer_price) ? Html::encode($model->offer_price) : '' ?></span> ₽</p>
    </div>
    <div class="ticket-card__desc">
      <p>
        <!-- ТЗ:" Анонс, не более 55 символов." -->
        <?php if (isset($model->offer_text)) : ?>
          <?php if (mb_strlen($model->offer_text) > Yii::$app->params['offerTextLength']) : ?>
            <?= Html::encode(mb_substr($model->offer_text, 0, Yii::$app->params['offerTextLength']) . '...') ?>
          <?php else : ?>
            <?= Html::encode($model->offer_text) ?>
          <?php endif; ?>
        <?php endif; ?>
      </p>
    </div>
  </div>
</div>
