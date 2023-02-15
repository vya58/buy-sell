<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Форма поиска товаров
 *
 */
class SearchWidget extends Widget
{
  public $errorCode;

  public function run()
  {
    return $this->render('search', ['errorCode' => $this->errorCode]);
  }
}
