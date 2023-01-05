<?php

namespace app\models\forms;

use yii\base\Model;
use app\models\Offer;

class OfferSearchForm extends Model
{
  public string $search = '';

  /**
   * @inheritDoc
   */
  public function rules(): array
  {
    return [
      [['search'], 'required'],
      [['search'], 'string', 'max' => Offer::MAX_LENGTH_TICKETNAME],
    ];
  }

  /**
   * @inheritDoc
   */
  public function attributeLabels()
  {
    return [
      'search' => 'Поиск',
    ];
  }

  /**
   * Метод автозаполнения поискового поля формы редактирования объявления данными из БД
   *
   * @param object $form - форма настройки профиля пользователя
   * @param string|null $search - сохраненный поисковый запрос
   */
  public function autocompleteForm($form, $search = null): void
  {
    $form->search = $search;
  }
}
