<?php

/** @var yii\web\View $this */

use app\widgets\CategoryWidget;
use yii\helpers\Html;
use yii\widgets\ListView;

?>

<section class="categories-list">
  <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
  <?= CategoryWidget::widget(['offerCategories' => $offerCategories, 'contextId' => $this->context->id]) ?>
</section>
<section class="tickets-list">
  <h2 class="visually-hidden">Предложения из категории <?= Html::encode($category->category_name) ?></h2>
  <div class="tickets-list__wrapper">
    <div class="tickets-list__header">
      <p class="tickets-list__title"><?= Html::encode($category->category_name) ?> <b class="js-qty"><?= Html::encode($countOffers) ?></b></p>
    </div>

    <?= ListView::widget(
      [
        'dataProvider' => $dataProvider,
        'itemView' => '_offers',
        'layout' => "<ul>{items}</ul>\n<div class='tickets-list__pagination'>{pager}</div>",
        'summary' => false,
        'emptyText' => 'Объявления отсутствуют',
        'emptyTextOptions' => [
          'tag' => 'p',
          'class' => 'pagination',
        ],
        'itemOptions' => [
          'tag' => 'li',
          'class' => 'tickets-list__item',
        ],
        'pager' => [
          // Подключение кастомного MyLinkPager вместо yii\widgets\LinkPager из "коробки"
          'class' => 'app\widgets\MyLinkPager',
          'prevPageLabel' => false,
          'nextPageLabel' => 'дальше',
          'disableCurrentPageButton' => true,
          'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'active'],
          'options' => [
            'tag' => 'ul',
            'class' => 'pagination',
          ],
        ],
      ]
    ) ?>
  </div>
</section>
