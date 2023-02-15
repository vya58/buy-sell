<?php

use app\models\forms\OfferSearchForm;
use yii\widgets\ActiveForm;

?>


<?php
$model = new OfferSearchForm();
$searchClass = 'search';

if (isset($errorCode)) {
  $searchClass = 'error__search search search--small';
}

if (isset($this->params['query'])) {
  $model->autocompleteForm($model, $this->params['query']);
}

$form = ActiveForm::begin([
  'method' => 'get',
  'action' => ['/site/search'],
  'options' => [
    'class' => $searchClass,
    'autocomplete' => 'off',
  ],
]); ?>
<?= $form->field($model, 'search')->input(['placeholder' => 'Поиск'])->label(false) ?>
<div class="search__icon"></div>
<div class="search__close-btn"></div>
<?php ActiveForm::end(); ?>
