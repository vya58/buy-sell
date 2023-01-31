<?php



use yii\helpers\Html;
use \yii\helpers\Url;

?>

<div class="buyer">
  <div class="buyer-link">
    <a href="<?= Url::to(['offers/index', 'id' => $offer->offer_id, 'buyerId' => $model->user_id]); ?>">
      <?php if (mb_strlen($model->name) > Yii::$app->params['maxNameLength']) : ?>
        <?= Html::encode(mb_substr($model->name, 0, Yii::$app->params['maxNameLength']) . '...') ?>
      <?php else : ?>
        <?= Html::encode($model->name) ?>
      <?php endif; ?>
    </a>
  </div>
  <div class="<?= 'buyer-avatar' ?>">
    <img src="<?= $model->avatar ? Html::encode('/uploads/avatars/' . $model->avatar) : '/img/avatar.jpg' ?>" srcset="<?= $model->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
  </div>
</div>
