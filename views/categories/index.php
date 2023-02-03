<?php

/** @var yii\web\View $this */

use app\models\Offer;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ListView;

?>

<section class="categories-list">
  <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
  <ul class="categories-list__wrapper">
    <?php $categoryIds = []; ?>
    <?php foreach ($offerCategories as $offerCategory) : ?>
      <?php if (!ArrayHelper::isIn($offerCategory->category->category_id, $categoryIds)) : ?>
        <?php $countOffersInCategory = $offerCategory->getCountOffersInCategory($offerCategory->category->category_id); ?>
        <li class="categories-list__item">
          <a href="<?= Url::to(['categories/index', 'id' => $offerCategory->category->category_id]) ?>" class="category-tile category-tile--default">
            <span class="category-tile__image">
              <img src="<?= Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . Offer::getImageOfRandomOffers($offerCategory)) ?>" alt="Иконка категории">
            </span>
            <span class="category-tile__label"><?= Html::encode($offerCategory->category->category_name) ?> <span class="category-tile__qty js-qty"><?= Html::encode($countOffersInCategory) ?></span></span>
          </a>
        </li>
      <?php endif; ?>
      <?php $categoryIds[] = $offerCategory->category->category_id; ?>
    <?php endforeach; ?>
  </ul>
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
