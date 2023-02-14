<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="buyer">
  <div class="buyer-link">
    <a href="<?= isset($offer->offer_id) ? Url::to(['offers/index', 'id' => $offer->offer_id, 'buyerId' => $model->user_id, 'currentPage' => $dataProvider->pagination->getPage()]) : '' ?>">
      <!--Условие, чтобы имя покупателя не вылезало за пределы элемента-->
      <?php if (isset($model->name) && (mb_strlen($model->name) > Yii::$app->params['maxNameLength'])) : ?>
        <?= Html::encode(mb_substr($model->name, 0, Yii::$app->params['maxNameLength']) . '...') ?>
      <?php elseif (isset($model->name)) : ?>
        <?= Html::encode($model->name) ?>
      <?php endif; ?>
    </a>
  </div>
  <div class="<?= Html::encode('buyer-avatar') ?>">
    <img src="<?= isset($model->avatar) ? Html::encode('/uploads/avatars/' . $model->avatar) : '/img/avatar.jpg' ?>" srcset="<?= isset($model->avatar) ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
  </div>
</div>
