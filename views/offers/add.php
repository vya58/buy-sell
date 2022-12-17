<?php

use app\models\Category;
use app\models\Offer;
use app\models\forms\OfferAddForm;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use \yii\helpers\Html;

/** @var yii\web\View $this */
/** @var OfferAddForm $offerAddForm */

?>

<section class="ticket-form">
  <div class="ticket-form__wrapper">
    <h1 class="ticket-form__title">Новая публикация</h1>
    <div class="ticket-form__tile">

      <?php $form = ActiveForm::begin([
        'id' => 'offer-add-form',
        'method' => 'post',
        'options' => [
          'class' => 'ticket-form__form form',
          'enctype' => 'multipart/form-data',
          'autocomplete' => 'off',
        ]
      ]); ?>
      <div class="ticket-form__avatar-container js-preview-container">
        <div class="ticket-form__avatar js-preview"></div>
        <div class="ticket-form__field-avatar">
          <?= $form->field($offerAddForm, 'offerImage')->fileInput(['class' => 'visually-hidden js-file-field', 'placeholder' => 'Загрузить фото…'])->label('<span class="ticket-form__text-upload">Загрузить фото…</span><span class="ticket-form__text-another">Загрузить другое фото…</span>') ?>
          <!--
          <input type="file" id="avatar" name="avatar" class="visually-hidden js-file-field">
              <label for="avatar">
                <span class="ticket-form__text-upload">Загрузить фото…</span>
                <span class="ticket-form__text-another">Загрузить другое фото…</span>
              </label>
          -->
        </div>
      </div>
      <div class="ticket-form__content">
        <div class="ticket-form__row">
          <div class="form__field">
            <?= $form->field($offerAddForm, 'offerTitle')->textInput(['options' => ['class' => 'js-field']])->label('Название') ?>
            <!--
          <input type="text" name="ticket-name" id="ticket-name" class="js-field" required="">
                <label for="ticket-name">Название</label>
          -->
            <span>Обязательное поле</span>
          </div>
        </div>
        <div class="ticket-form__row">
          <div class="form__field">
            <?= $form->field($offerAddForm, 'offerText')->textarea(['cols' => 30, 'rows' => 10, 'options' => ['class' => 'js-field']])->label('Описание') ?>
            <!--
          <textarea name="comment" id="comment-field" cols="30" rows="10" class="js-field"></textarea>
                <label for="comment-field">Описание</label>
          -->
            <span>Обязательное поле</span>
          </div>
        </div>
        <div class="ticket-form__row">
          <?= $form->field($offerAddForm, 'categories')->dropDownList(ArrayHelper::map(
            Category::find()->all(),
            'category_id',
            'category_name'
          ),/*$categories,*/ ['class' => 'form__select js-multiple-select', 'placeholder' => "Выбрать категорию публикации", 'multiple' => true])->label(false); ?>
          <!--
          <select name="category" id="category-field" data-label="Выбрать категорию публикации" class="form__select js-multiple-select">
            <option value="1">Дом</option>
            <option value="2">Спорт и отдых</option>
            <option value="3">Авто</option>
            <option value="4">Электроника</option>
            <option value="5">Одежда</option>
            <option value="6">Книги</option>
          </select>
          -->
        </div>
        <div class="ticket-form__row">
          <div class="form__field form__field--price">
            <?= $form->field($offerAddForm, 'offerPrice')->input('number', ['options' => ['class' => 'js-field', 'min' => 1]])->label('Цена') ?>
            <!--
            <input type="number" name="price" id="price-field" class="js-field js-price" min="1" required="">
            <label for="price-field">Цена</label>
            -->
            <span>Обязательное поле</span>
          </div>
          <div class="form__switch switch">
            <?= $form->field($offerAddForm, 'offerType')->radioList(
              [
                Offer::OFFER_TYPE['buy'] => 'Куплю',
                Offer::OFFER_TYPE['sell'] => 'Продам'
              ],
              [
                'class' => 'form__switch switch',
                'item' => function ($index, $label, $name, $checked, $value) {
                  return

                    Html::beginTag('div', ['class' => 'switch__item']) .
                    Html::radio($name, $checked, ['value' => $value, 'id' => $index, 'class' => 'visually-hidden']) .
                    Html::label($label, $index, ['class' => 'switch__button']) .
                    Html::endTag('div');
                }
              ]

            )->label(false) ?>
            <!--
              <input type="radio" id="buy-field" name="action" value="buy" class="visually-hidden">
              <label for="buy-field" class="switch__button">Куплю</label>
              -->

            <!--
            <div class="switch__item">

              <input type="radio" id="sell-field" name="action" value="sell" class="visually-hidden">
              <label for="sell-field" class="switch__button">Продам</label>

            </div>
           -->
          </div>
        </div>
      </div>

      <button class="form__button btn btn--medium js-button" type="submit">Опубликовать</button>
      <?php ActiveForm::end(); ?>

    </div>
  </div>
</section>
