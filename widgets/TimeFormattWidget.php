<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Отображает секцию самых новых предложений
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