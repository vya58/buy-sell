<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\widgets\OfferImageWidget;


?>

<ul class="<?= $contextId === 'offers' ? 'ticket__tags' : 'categories-list__wrapper' ?>">
  <?php $categoryIds = []; ?>
  <?php if (count($offerCategories)) : ?>
    <?php foreach ($offerCategories as $offerCategory) : ?>
      <?php if (isset($offerCategory->category->category_id)) : ?>
        <?php if (!ArrayHelper::isIn($offerCategory->category->category_id, $categoryIds)) : ?>
          <?php $countOffersInCategory = $offerCategory->getCountOffersInCategory($offerCategory->category->category_id); ?>
          <li class="<?= $contextId === 'offers' ? '' : 'categories-list__item' ?>">
            <a href="<?= Url::to(['categories/', 'id' => $offerCategory->category->category_id]) ?>" class="category-tile category-tile--<?= $contextId === 'offers' ? 'small' : 'default' ?>">
              <span class="category-tile__image">
                <!-- ТЗ: "Для изображений категорий отображаются случайные изображения" -->
                <?= OfferImageWidget::widget(['offerImage' => $offerCategory->offer->offer_image]) ?>
              </span>
              <span class="category-tile__label"><?= isset($offerCategory->category->category_name) ? Html::encode($offerCategory->category->category_name) : '' ?>
                <?php if ($contextId !== 'offers') : ?>
                  <span class="category-tile__qty js-qty"><?= Html::encode($countOffersInCategory) ?></span>
                <?php endif; ?>
              </span>
            </a>
          </li>
        <?php endif; ?>
        <?php $categoryIds[] = $offerCategory->category->category_id; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>
</ul>
