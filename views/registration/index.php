<?php

/**
 * @var yii\web\View $this
 */

use yii\widgets\ActiveForm;
use app\models\Auth;
use app\models\forms\RegistrationForm;
use \yii\helpers\Url;
use yii\authclient\widgets\AuthChoice;

/** @var yii\web\View $this */
/** @var RegistrationForm $registrationForm */
?>

<section class="sign-up">
  <h1 class="visually-hidden">Регистрация</h1>
  <?php $form = ActiveForm::begin([
    'id' => 'registration-form',
    'method' => 'post',
    'options' => [
      'class' => 'sign-up__form form',
      'enctype' => 'multipart/form-data',
      'autocomplete' => 'off',
    ],
  ]); ?>

  <div class="sign-up__title">
    <h2>Регистрация</h2>
    <a class="sign-up__link" href="<?= Url::to('/login') ?>">Вход</a>
  </div>
  <div class="sign-up__avatar-container js-preview-container">
    <div class="sign-up__avatar js-preview"></div>
    <div class="sign-up__field-avatar">
      <?= $form->field($registrationForm, 'avatar')->fileInput(['class' => 'visually-hidden js-file-field', 'placeholder' => 'Загрузить аватар…'])->label('<span class="sign-up__text-upload">Загрузить аватар…</span><span class="sign-up__text-another">Загрузить другой аватар…</span>') ?>
    </div>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'name')->textInput(['options' => ['class' => 'js-field']])->label('Имя и фамилия') ?>
    <span>Обязательное поле</span>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'email')->input(['options' => ['class' => 'js-field']])->label('Эл. почта') ?>
    <span>Неверный email</span>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'password')->passwordInput(['options' => ['class' => 'js-field']])->label('Пароль') ?>
    <span>Обязательное поле</span>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'passwordRepeat')->passwordInput(['options' => ['class' => 'js-field']])->label('Пароль еще раз') ?>
    <span>Пароли не совпадают</span>
  </div>

  <button class="sign-up__button btn btn--medium js-button" type="submit">Создать аккаунт</button>

  <a class="btn btn--small btn--flex btn--white" href="<?= Url::to(['login/auth', 'authclient' => 'vkontakte']) ?>">
    Войти через
    <span class="icon icon--vk"></span>
  </a>

  <?php ActiveForm::end(); ?>
</section>
