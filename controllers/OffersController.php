<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;

class OffersController extends Controller
{
  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex()
  {
    //Временная переменная для подключения статичных вариантов страницы.
    $comments = false;

    return $this->render(
      'index',
      [
        //'dataProvider' => $dataProvider,
        //'categories' => $categories,
        'comments' => $comments,
      ]
    );
  }
}
