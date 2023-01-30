<?php

/** @var yii\web\View $this */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use \yii\helpers\Url;
use yii\widgets\ListView;
use app\models\Offer;

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
          'layout' => "<div class='tickets-list__pagination'>{pager}</div>\n<ul class='search-results__list'>{items}</ul>",
          'itemOptions' => [
            'tag' => 'li',
            'class' => 'search-results__item',
          ],
          'pager' => [
            // Подключение кастомного MyLinkPager вместо yii\widgets\LinkPager из "коробки"
            //'class' => 'app\widgets\MyLinkPager',
            'prevPageLabel' => 'Предыдущие 8',
            'nextPageLabel' => 'Еще ' . Yii::$app->params['pageSize'],
            'pageCssClass' => 'visually-hidden',
            'prevPageCssClass' => 'tickets-list__title',
            'nextPageCssClass' => 'tickets-list__link',
            'disableCurrentPageButton' => true,
            'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'visually-hidden'],
            'linkContainerOptions' => [
              'tag' => 'div',
              'class' => 'tickets-list__header',
            ],
            'options' => [
              'tag' => 'ul',
              'class' => 'pagination',
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
    <div class="tickets-list__header">
      <p class="tickets-list__title">Самое свежее</p>
      <a href="#" class="tickets-list__link">Еще 25</a>
    </div>
    <ul>
      <?php foreach ($newOffers as $newOffer) : ?>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color01">
            <div class="ticket-card__img">
              <img src="<?= $newOffer->offer_image ? Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . $newOffer->offer_image) : Html::encode('../img/blank.png') ?>" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label"><?= Html::encode($newOffer->offer_type) ?></span>
              <div class="ticket-card__categories">
                <?php foreach ($newOffer->categories as $category) : ?>
                  <a href="#"><?= Html::encode($category->category_name) ?></a>
                <?php endforeach; ?>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="<?= Url::to(['offers/index', 'id' => $newOffer->offer_id]) ?>"><?= Html::encode($newOffer->offer_title) ?></a></h3>
                <p class="ticket-card__price"><span class="js-sum"><?= Html::encode($newOffer->offer_price) ?></span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>
                  <!-- ТЗ:" Анонс, не более 55 символов." -->
                  <?php if (mb_strlen($newOffer->offer_text) > Yii::$app->params['offerTextLength']) : ?>
                    <?= Html::encode(mb_substr($newOffer->offer_text, 0, Yii::$app->params['offerTextLength']) . '...') ?>
                  <?php else : ?>
                    <?= Html::encode($newOffer->offer_text) ?>
                  <?php endif; ?>
                </p>
              </div>
            </div>
          </div>
        </li>
      <?php endforeach; ?>
    </ul>
  </div>
</section>
