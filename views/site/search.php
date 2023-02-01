<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use yii\widgets\ListView;
use app\widgets\NewTicketWidget;

?>

<section class="search-results">

  <h1 class="visually-hidden">Результаты поиска</h1>
  <div class="search-results__wrapper">
    <?php if ($dataProvider->totalCount === 0) : ?>
      <div class="search-results__message">
        <p>Не найдено <br>ни&nbsp;одной публикации</p>
      </div>
    <?php else : ?>
      <p class="search-results__label">Найдено <span class="js-results"><?= Html::encode($dataProvider->totalCount) ?> публикации</span></p>
      <?= ListView::widget(
        [
          'dataProvider' => $dataProvider,
          'itemView' => '_search',
          'layout' => "{pager}\n<ul class='search-results__list'>{items}</ul>",
          'itemOptions' => [
            'tag' => 'li',
            'class' => 'search-results__item',
          ],
          'pager' => [
            'prevPageLabel' => 'Предыдущие 8',
            'nextPageLabel' => 'Еще ' . Yii::$app->params['pageSize'],
            'pageCssClass' => 'visually-hidden',
            'prevPageCssClass' => 'tickets-list__link',
            'nextPageCssClass' => 'tickets-list__link',
            'disableCurrentPageButton' => true,
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'visually-hidden'],
            'linkContainerOptions' => [
              'tag' => 'p',
              'class' => 'tickets-list__title',
            ],
            'options' => [
              'tag' => 'a',
              'class' => 'tickets-list__link',
            ],
          ],
        ]
      ) ?>
    <?php endif; ?>
  </div>
</section>
<section class="tickets-list">
  <h2 class="visually-hidden">Самые новые предложения</h2>
  <div class="tickets-list__wrapper">
    <?= NewTicketWidget::widget(['dataProvider' => $newOffersdataProvider]) ?>
</section>
