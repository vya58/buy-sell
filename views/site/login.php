<?php

use yii\widgets\ActiveForm;
use app\models\forms\LoginForm;
use yii\helpers\Url;

/** @var yii\web\View $this */
/** @var yii\widgets\ActiveForm $form */
/** @var app\models\forms\LoginForm $loginForm */

?>
<section class="login">
  <h1 class="visually-hidden">Логин</h1>

  <?php $form = ActiveForm::begin([
    'id' => 'login-form',
    'method' => 'post',
    'options' => [
      'class' => 'login__form form',
      'enctype' => 'multipart/form-data',
      'autocomplete' => 'off',
    ],
  ]); ?>
  <div class="login__title">
    <a class="login__link" href="<?= Url::to('registration') ?>">Регистрация</a>
    <h2>Вход</h2>
  </div>
  <div class="form__field login__field">
    <?= $form->field($loginForm, 'email')->input(['options' => ['class' => 'js-field']])->label('Эл. почта') ?>
    <span>Обязательное поле</span>
  </div>
  <div class="form__field login__field">
    <?= $form->field($loginForm, 'password')->passwordInput(['options' => ['class' => 'js-field']])->label('Пароль') ?>
    <span>Обязательное поле</span>
  </div>
  <button class="login__button btn btn--medium js-button" type="submit">Войти</button>
  <a class="btn btn--small btn--flex btn--white" href="#">
    Войти через
    <span class="icon icon--vk"></span>
  </a>
  <?php ActiveForm::end(); ?>

</section>
