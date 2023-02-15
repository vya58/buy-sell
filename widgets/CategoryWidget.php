<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Отображает секцию категорий
 *
 */
class CategoryWidget extends Widget
{
  public $offerCategories;
  public $contextId;

  public function run()
  {
    return $this->render('categories', ['offerCategories' => $this->offerCategories, 'contextId' => $this->contextId]);
  }
}
