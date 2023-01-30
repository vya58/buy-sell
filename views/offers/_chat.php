<?php



use yii\helpers\Html;
use \yii\helpers\Url;
use app\models\Category;
use app\models\Offer;
use app\models\User;
use yii\widgets\ActiveForm;
use app\assets\FirebaseAsset;
use yii\widgets\Pjax;

//FirebaseAsset::register($this);
?>



<div class="buyer">
  <?php //foreach ($buyers as $buyer) :
  ?>
  <div class="buyer-link">
    <a href="<?= Url::to(['offers/index', 'id' => $offer->offer_id, 'buyerId' => $model->user_id]); ?>"><?= Html::encode($model->name) ?></a>
  </div>
  <div class="<?= 'buyer-avatar' ?>">
    <img src="<?= $model->avatar ? Html::encode('/uploads/avatars/' . $model->avatar) : '/img/avatar.jpg' ?>" srcset="<?= $model->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
  </div>

  <?php // endforeach;
  ?>
</div>
