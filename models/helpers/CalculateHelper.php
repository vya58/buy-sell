<?php

namespace app\models\helpers;


class CalculateHelper
{
  /**
   * Метод поиска элементов многомерного массива по ключу c требуемым значением
   * Взято и переработано здесь: https://ru.stackoverflow.com/questions/806243/%D0%9F%D0%BE%D0%B8%D1%81%D0%BA-%D0%BA%D0%BB%D1%8E%D1%87%D0%B0-%D0%B2-%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE%D0%BC%D0%B5%D1%80%D0%BD%D0%BE%D0%BC-%D0%BC%D0%B0%D1%81%D1%81%D0%B8%D0%B2%D0%B5-php
   * @param string $searchKey Ключ который ищем
   * @param array $arr Массив в котором ищем
   * @param array $result Массив в который будет складываться результат (передается по ссылке) перед использованием - обнулить $result = []
   */
  public static function searchKey($searchKey, array $array, array &$result, $searchValue = false): void
  {
    $value = $searchValue;
    // Если в массиве есть элемент с ключем $searchKey и он пустой , то кладём сообщение в результат
    if (isset($array[$searchKey]) && $array[$searchKey] === $value) {
      $result[] = $array;
    }
    // Обходим все элементы массива в цикле
    foreach ($array as $key => $param) {
      // Если элемент массива - массив, то вызываем рекурсивно эту функцию
      if (is_array($param)) {
        self::searchKey($searchKey, $param, $result, $value);
      }
    }
  }
}
