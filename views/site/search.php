<?php

/** @var yii\web\View $this */

use app\models\helpers\CalculatePageHelper;
use app\widgets\NewTicketWidget;
use yii\helpers\Html;
use yii\widgets\ListView;


?>

<section class="search-results">
  <h1 class="visually-hidden">Результаты поиска</h1>
  <div class="search-results__wrapper">
    <?php if (isset($dataProvider->totalCount)) : ?>
      <?php if (!$dataProvider->totalCount) : ?>
        <div class="search-results__message">
          <p>Не найдено <br>ни&nbsp;одной публикации</p>
        </div>
      <?php else : ?>
        <p class="search-results__label"><?= $dataProvider->totalCount !== 1 ? 'Найдено ' : 'Найдена ' ?><span class="js-results"><?= Html::encode($dataProvider->totalCount) ?> публикации</span></p>
        <?= ListView::widget(
          [
            'dataProvider' => $dataProvider,
            'itemView' => '_search',
            'layout' => "<div class='tickets-list__header'>{pager}</div>\n<ul class='search-results__list'>{items}</ul>",
            'itemOptions' => [
              'tag' => 'li',
              'class' => 'tickets-list__item',
            ],
            'pager' => [
              'prevPageLabel' => 'Предыдущие ' . Yii::$app->params['pageSize'],
              'nextPageLabel' => 'Еще ' . CalculatePageHelper::numberModelsTheNextPage($dataProvider, 'page-search'),
              'pageCssClass' => 'visually-hidden tickets-list__link',
              'prevPageCssClass' => 'tickets-list__link',
              'nextPageCssClass' => 'tickets-list__title',
              'disableCurrentPageButton' => true,
              'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'visually-hidden'],
              'linkContainerOptions' => [
                'tag' => 'p',
                'class' => 'tickets-list__title',
              ],
              'options' => [
                'tag' => false,
              ],
            ],
          ]
        ) ?>
      <?php endif; ?>
    <?php endif; ?>
  </div>
</section>
<section class="tickets-list">
  <h2 class="visually-hidden">Самые новые предложения</h2>
  <div class="tickets-list__wrapper">
    <?= $newOffersdataProvider ? NewTicketWidget::widget(['dataProvider' => $newOffersdataProvider]) : '' ?>
</section>
