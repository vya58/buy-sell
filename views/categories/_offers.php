<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\OfferImageWidget;
use app\widgets\TrimmingStringWidget;

?>

<div class="ticket-card ticket-card--color06">
  <div class="ticket-card__img">
  <?= OfferImageWidget::widget(['offerImage' => $model->offer_image]) ?>
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
          <?= TrimmingStringWidget::widget(['text' => $model->offer_text, 'textLength' => Yii::$app->params['offerTextLength']]) ?>
        <?php endif; ?>
      </p>
    </div>
  </div>
</div>
