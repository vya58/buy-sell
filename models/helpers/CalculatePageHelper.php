<?php

namespace app\models\helpers;

use yii\data\ActiveDataProvider;
use Yii;

class CalculatePageHelper
{
  /**
   * Метод вычисления количества выводимых моделей на сдедующей страницы пагинации
   *
   * @param  ActiveDataProvider $dataProvider - дата провайдер
   *
   * @return int $remainingNumberOfAds - количество выводимых моделей на сдедующей страницы пагинации
   */
  public static function numberModelsTheNextPage(ActiveDataProvider $dataProvider, string $queryParams): int
  {
    $remainingNumberOfAds = $dataProvider->totalCount - Yii::$app->params['pageSize'];
    if ($remainingNumberOfAds > Yii::$app->params['pageSize']) {
      $remainingNumberOfAds = Yii::$app->params['pageSize'];
    }

    if (isset(Yii::$app->request->queryParams[$queryParams])) {
      $currentPageNumber = Yii::$app->request->queryParams[$queryParams];
      $displayedNumberOfAds = $currentPageNumber * Yii::$app->params['pageSize'];
      $remainingNumberOfAds = $dataProvider->totalCount - $displayedNumberOfAds;
    };
    return $remainingNumberOfAds;
  }
}
