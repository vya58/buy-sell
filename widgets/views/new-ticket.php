<?php

use app\models\helpers\CalculatePageHelper;
use yii\widgets\ListView;

?>


<?= ListView::widget(
  [
    'dataProvider' => $dataProvider,
    'itemView' => '_new-ticket',
    'layout' => "<div class='tickets-list__header'><p class='tickets-list__title'>Самое свежее</p>{pager}</div>\n<ul class='search-results__list'>{items}</ul>",
    'itemOptions' => [
      'tag' => 'li',
      'class' => 'tickets-list__item',
    ],
    'pager' => [
      'prevPageLabel' => 'Предыдущие ' . Yii::$app->params['pageSize'],
      'nextPageLabel' => 'Еще ' . CalculatePageHelper::numberModelsTheNextPage($dataProvider, 'page-new'),
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
