<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Отображает секцию самых новых предложений
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
