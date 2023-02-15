<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Отображает секцию самых новых предложений
 *
 */
class TrimmingStringWidget extends Widget
{
  public $text;
  public $textLength;

  public function run()
  {
    return $this->render('trimming-string', ['text' => $this->text, 'textLength' => $this->textLength]);
  }
}
