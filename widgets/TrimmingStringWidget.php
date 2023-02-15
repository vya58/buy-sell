<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Выводит обрезанный текст, если длиннее чем 'textLength'
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
