<?php

namespace app\widgets;

use yii\base\Widget;

/**
 * Отображает секцию самых новых предложений
 *
 */
class NewTicketWidget extends Widget
{
    public $dataProvider;

    public function run()
    {
        return $this->render('new-ticket', ['dataProvider' => $this->dataProvider]);
    }
}
