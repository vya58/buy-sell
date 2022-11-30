<?php

/** @var yii\web\View $this
 * @var Task[] $tasks
 */

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use app\models\forms\RegistrationForm;

/** @var yii\web\View $this */
/** @var RegistrationForm $registrationForm */
?>

<section class="sign-up">

  <h1 class="visually-hidden">Регистрация</h1>
  <?php $form = ActiveForm::begin([
    'id' => 'registration-form',
    //'enableAjaxValidation' => true,

    //'method' => 'post',
    'options' => [
      'class' => 'sign-up__form form',
      'enctype' => 'multipart/form-data',
      'autocomplete' => 'off',
    ],
    //'template' => '{input}{label}',
  ]); ?>

  <div class="sign-up__title">
    <h2>Регистрация</h2>
    <a class="sign-up__link" href="login.html">Вход</a>
  </div>
  <div class="sign-up__avatar-container js-preview-container">
    <div class="sign-up__avatar js-preview"></div>
    <div class="sign-up__field-avatar">
      <?= $form->field($registrationForm, 'avatar')->fileInput(['multiple' => false, 'class' => 'visually-hidden js-file-field', 'placeholder' => 'Загрузить аватар…']); ?>

      <!--
      <input type="file" id="avatar" name="avatar" class="visually-hidden js-file-field">

      <label for="avatar">
        <span class="sign-up__text-upload">Загрузить аватар…</span>
        <span class="sign-up__text-another">Загрузить другой аватар…</span>
      </label>
-->
    </div>
  </div>
  <div class="form__field sign-up__field">

    <?= $form->field($registrationForm, 'name', ['options' => ['class' => 'js-field']])->textInput()->label('Имя и фамилия') ?>

    <!--
    <input type="text" name="user-name" id="user-name" class="js-field" required="">
    <label for="user-name">Имя и фамилия</label>
    -->
    <span>Обязательное поле</span>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'email', ['options' => ['class' => 'js-field']])/*->input('email')->hint('Введите адрес почты')*/->label('Эл. почта') ?>

    <!--
    <input type="email" name="user-email" id="user-email" class="js-field" required="">
    <label for="user-email">Эл. почта</label>
    -->
    <span>Неверный email</span>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'password', ['options' => ['class' => 'js-field']])->passwordInput()->label('Пароль') ?>

    <!--
    <input type="password" name="user-password" id="user-password" class="js-field" required="">
    <label for="user-password">Пароль</label>
    -->
    <span>Обязательное поле</span>
  </div>
  <div class="form__field sign-up__field">
    <?= $form->field($registrationForm, 'passwordRepeat', ['options' => ['class' => 'js-field']])->passwordInput()->label('Пароль еще раз') ?>

    <!--
    <input type="password" name="user-password-again" id="user-password-again" class="js-field" required="">
    <label for="user-password-again">Пароль еще раз</label>
    -->
    <span>Пароли не совпадают</span>
  </div>


  <button class="sign-up__button btn btn--medium js-button" type="submit">Создать аккаунт</button>

  <a class="btn btn--small btn--flex btn--white" href="#">
    Войти через
    <span class="icon icon--vk"></span>
  </a>

  <?php ActiveForm::end(); ?>
</section>
