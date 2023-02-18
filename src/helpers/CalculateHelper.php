<?php

namespace app\src\helpers;


class CalculateHelper
{
  /**
   * Метод поиска элементов многомерного массива по ключу c требуемым значением
   * Взято и переработано здесь: https://ru.stackoverflow.com/questions/806243/%D0%9F%D0%BE%D0%B8%D1%81%D0%BA-%D0%BA%D0%BB%D1%8E%D1%87%D0%B0-%D0%B2-%D0%BC%D0%BD%D0%BE%D0%B3%D0%BE%D0%BC%D0%B5%D1%80%D0%BD%D0%BE%D0%BC-%D0%BC%D0%B0%D1%81%D1%81%D0%B8%D0%B2%D0%B5-php
   *
   * @param string $searchKey Ключ который ищем
   * @param array $processedArray Массив в котором ищем (обрабатываемый массив)
   * @param array $result Массив в который будет складываться результат (передается по ссылке) перед использованием - обнулить $result = []
   * @param $searchValue - значение массива, которому соответствуют значения искомых ключей
   */
  public static function searchKey($searchKey, array $processedArray, array &$result, $searchValue): void
  {
    $value = $searchValue;
    // Если в массиве есть элемент с ключем $searchKey и он пустой , то кладём сообщение в результат
    if (isset($processedArray[$searchKey]) && $processedArray[$searchKey] === $value) {
      $result[] = $processedArray;
    }
    // Обходим все элементы массива в цикле
    foreach ($processedArray as $key => $param) {
      // Если элемент массива - массив, то вызываем рекурсивно эту функцию
      if (is_array($param)) {
        self::searchKey($searchKey, $param, $result, $value);
      }
    }
  }

  /**
   * Метод сортировки многомерного массива по значению ключей второго уровня вложенности
   *
   * @param array $sortableArray - сортируемый массив
   * @param $sortedValue - значение ключей второго уровня вложенности массива $sortableArray, по которым происходит сортировка
   *
   * @return array $groups - массив, где ключи первого уровня вложенности - уникальные значения ключей второго уровня вложенности массива $sortableArray, а их значения - индексированные массивы со всеми значениями, соответствующими значению $sortedValue, найденные в сортируемом массиве $sortableArray
   */
  public static function sortArrayByKeyValue(array $sortableArray, $sortedValue): array
  {
    $groups = [];

    foreach ($sortableArray as $element) {
      if (array_key_exists($sortedValue, $element)) {
        $key = $element[$sortedValue];
      }

      if (!array_key_exists($key, $groups)) {
        $groups[$key] = [];
      }

      $groups[$key][] = $element;
    }
    return $groups;
  }
}
