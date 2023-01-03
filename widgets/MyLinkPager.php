<?php

/**
 * Переопределение метода "renderPageButton()" класса "yii\widgets\LinkPager"
 *
 * В виджете "yii\widgets\ListView" можно присвоить класс лабели активной страницы, но не ссылке внутри её.
 * В разметке же проекта класс "active" присваивается ссылке и именно её CSS использует при стилизации кнопок пейджера.
 * Также согласно ТЗ кнопка активной страницы пагинации не имееет ссылки, т.е. "'disableCurrentPageButton' => true,".
 * Это было мной и использовано.
 * Когда страница активна, тегу внутри кнопки присваивается класс "active": 'disabledListItemSubTagOptions' => ['tag' => 'a', 'class' => 'active'].
 * Но тогда, если страница последняя, тот же класс "active" присваивается и тегу внутри кнопки "nextPage".
 * Чтобы избавиться от этого пришлось переопределить метод "renderPageButton()" класса "yii\widgets\LinkPager, добавив проверку при присваивании класса "active", что кнопка не имеет класс "next", т.е. не является кнопкой "nextPage".
 *
 */

namespace app\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\LinkPager;

class MyLinkPager extends LinkPager
{
  /**
   * Renders a page button.
   * You may override this method to customize the generation of page buttons.
   * @param string $label the text label for the button
   * @param int $page the page number
   * @param string $class the CSS class for the page button.
   * @param bool $disabled whether this page button is disabled
   * @param bool $active whether this page button is active
   * @return string the rendering result
   */
  protected function renderPageButton($label, $page, $class, $disabled, $active)
  {
    $options = $this->linkContainerOptions;
    $linkWrapTag = ArrayHelper::remove($options, 'tag', 'li');
    Html::addCssClass($options, empty($class) ? $this->pageCssClass : $class);

    if ($active) {
      Html::addCssClass($options, $this->activePageCssClass);
    }

    if ($disabled) {
      Html::addCssClass($options, $this->disabledPageCssClass);
      $disabledItemOptions = $this->disabledListItemSubTagOptions;

      // Не присваивать класс "active" ссылке кнопки nextPage
      if ($class === 'next') {
        $disabledItemOptions['class'] = '';
      }

      $tag = ArrayHelper::remove($disabledItemOptions, 'tag', 'span');

      return Html::tag($linkWrapTag, Html::tag($tag, $label, $disabledItemOptions), $options);
    }

    $linkOptions = $this->linkOptions;
    $linkOptions['data-page'] = $page;

    return Html::tag($linkWrapTag, Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
  }
}
