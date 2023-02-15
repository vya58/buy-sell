<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Выводит дату в требуемом формате
 *
 */
class TimeFormattWidget extends Widget
{
  public $date;

  public function run()
  {
    return $this->render('time-formatt', ['date' => $this->date]);
  }
}