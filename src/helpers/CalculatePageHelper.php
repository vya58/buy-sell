<?php

namespace app\src\helpers;

use yii\data\ActiveDataProvider;
use Yii;

class CalculatePageHelper
{
  /**
   * Метод вычисления количества выводимых моделей на следующей страницы пагинации
   *
   * @param  ActiveDataProvider $dataProvider - дата провайдер
   * @param string $queryParams - ключ параметра, значение которого берётся из url
   *
   * @return int $remainingNumberOfAds - количество выводимых моделей на сдедующей страницы пагинации
   */
  public static function numberModelsTheNextPage(ActiveDataProvider $dataProvider, string $queryParams): int
  {
    $pageSize = (int) Yii::$app->params['pageSize'];
    $remainingNumberOfAds = $dataProvider->totalCount - $pageSize;
    if ($remainingNumberOfAds > $pageSize) {
      $remainingNumberOfAds = $pageSize;
    }

    if (isset(Yii::$app->request->queryParams[$queryParams])) {
      $currentPageNumber = (int) Yii::$app->request->queryParams[$queryParams];
      $displayedNumberOfAds = $currentPageNumber * $pageSize;
      $remainingNumberOfAds = $dataProvider->totalCount - $displayedNumberOfAds;
    };
    return $remainingNumberOfAds;
  }
}
