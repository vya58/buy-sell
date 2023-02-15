<?php

use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\TrimmingStringWidget;

?>

<div class="buyer">
  <div class="buyer-link">
    <a href="<?= isset($offer->offer_id) ? Url::to(['offers/index', 'id' => $offer->offer_id, 'buyerId' => $model->user_id, 'currentPage' => $dataProvider->pagination->getPage()]) : '' ?>">
      <!--Условие, чтобы имя покупателя не вылезало за пределы элемента-->
      <?= TrimmingStringWidget::widget(['text' => $model->name, 'textLength' => Yii::$app->params['maxNameLength']]) ?>
    </a>
  </div>
  <div class="<?= Html::encode('buyer-avatar') ?>">
    <img src="<?= isset($model->avatar) ? Html::encode('/uploads/avatars/' . $model->avatar) : '/img/avatar.jpg' ?>" srcset="<?= isset($model->avatar) ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
  </div>
</div>
