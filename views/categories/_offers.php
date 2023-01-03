<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\models\Offer;

?>

<div class="ticket-card ticket-card--color06">
  <div class="ticket-card__img">
    <img src="<?= $model->offer_image ? Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $model->offer_image) : Html::encode('/img/blank.png') ?>" alt="Изображение товара">
  </div>
  <div class="ticket-card__info">
    <span class="ticket-card__label"><?= Html::encode($model->offer_type) ?></span>
    <div class="ticket-card__categories">
      <a href="<?= Url::to(['categories/index', 'id' => $model->offerCategories[0]['category_id']]) ?>"><?= Html::encode($model->categories[0]['category_name']) ?></a>
    </div>
    <div class="ticket-card__header">
      <h3 class="ticket-card__title"><a href="<?= Url::to(['offers/index', 'id' => $model->offer_id]) ?>"><?= Html::encode($model->offer_title) ?></a></h3>
      <p class="ticket-card__price"><span class="js-sum"><?= Html::encode($model->offer_price) ?></span> ₽</p>
    </div>
    <div class="ticket-card__desc">
      <p>
        <!-- ТЗ:" Анонс, не более 55 символов." -->
        <?php if (mb_strlen($model->offer_text) > Yii::$app->params['offerTextLength']) : ?>
          <?= Html::encode(mb_substr($model->offer_text, 0, Yii::$app->params['offerTextLength']) . '...') ?>
        <?php else : ?>
          <?= Html::encode($model->offer_text) ?>
        <?php endif; ?>
      </p>
    </div>
  </div>
</div>
