<?php

namespace app\src\service;

use app\models\OfferCategory;

/**
 * Класс обработки app\models\OfferCategory
 */
class OfferCategoryService
{

  /**
   * Метод получения количества записей (т.е. объявлений), относящихся к категории с id, равным $categoryId
   *
   * @param int $categoryId - id категории
   *
   * @return int - количество записей
   */
  public static function getCountOffersInCategory(int $categoryId): int
  {
    return OfferCategory::find()
      ->where(['category_id' => $categoryId])
      ->count();
  }

  /**
   * Метод получения всех записей OfferCategory с "жадной" загрузкой Category
   *
   * @return array - OfferCategory
   */
  public static function getOfferCategories(): array
  {
    return OfferCategory::find()
      ->with('category')
      ->all();
  }
}
