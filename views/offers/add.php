<?php

use app\models\Category;
use app\models\Offer;
use app\models\forms\OfferAddForm;
use yii\helpers\ArrayHelper;
use \yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\widgets\OfferImageWidget;

/** @var yii\web\View $this */
/** @var OfferAddForm $offerAddForm */

?>

<section class="ticket-form">
  <div class="ticket-form__wrapper">
    <h1 class="ticket-form__title"><?= $ticketFormTitle ? Html::encode($ticketFormTitle) : '' ?></h1>
    <div class="ticket-form__tile">
      <?php $form = ActiveForm::begin([
        'id' => 'offer-add-form',
        'method' => 'post',
        'options' => [
          'class' => 'ticket-form__form form',
          'enctype' => 'multipart/form-data',
          'autocomplete' => 'off',
        ],
      ]); ?>
      <?php if (isset($offerAddForm->offerImage)) : ?>
        <div class="ticket-form__avatar-container js-preview-container <?= $offerAddForm->offerImage  ? 'uploaded' : '' ?>">
          <div class="ticket-form__avatar js-preview">
          <?= OfferImageWidget::widget(['offerImage' => $offerAddForm->offerImage]) ?>
          </div>
          <div class="ticket-form__field-avatar">
            <?= $form->field($offerAddForm, 'offerImage')->fileInput(['class' => 'visually-hidden js-file-field', 'placeholder' => 'Загрузить фото…'])->label('<span class="ticket-form__text-upload">Загрузить фото…</span><span class="ticket-form__text-another">Загрузить другое фото…</span>') ?>
          </div>
        </div>
      <?php endif; ?>
      <div class="ticket-form__content">
        <div class="ticket-form__row">
          <div class="form__field">
            <?= $form->field($offerAddForm, 'offerTitle')->textInput(['class' => 'js-field'])->label('Название') ?>
            <span>Обязательное поле</span>
          </div>
        </div>
        <div class="ticket-form__row">
          <div class="form__field">
            <?= $form->field($offerAddForm, 'offerText')->textarea(['cols' => 30, 'rows' => 10, 'class' => 'js-field'])->label('Описание') ?>
            <span>Обязательное поле</span>
          </div>
        </div>

        <?= $form->field($offerAddForm, 'categories', ['options' => ['tag' => 'div', 'class' => 'ticket-form__row']])->dropDownList(ArrayHelper::map(
          Category::find()->all(),
          'category_id',
          'category_name'
        ), ['class' => 'form__select js-multiple-select', 'placeholder' => 'Выбрать категорию публикации', 'multiple' => true])->label(false); ?>

        <div class="ticket-form__row">
          <?= $form->field($offerAddForm, 'offerPrice', ['options' => ['tag' => 'div', 'class' => 'form__field form__field--price']])->input('number', ['class' => 'js-field', 'min' => 1, 'template' => "{input}<span>Обязательное поле</span>", 'placeholder' => 'Цена'])->label(false) ?>
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
          </div>
        </div>
      </div>
      <button class="form__button btn btn--medium js-button" type="submit">Опубликовать</button>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</section>
