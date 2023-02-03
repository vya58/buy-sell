<?php

namespace app\models\forms;

use app\models\Offer;
use yii\base\Model;

class OfferSearchForm extends Model
{
  public string $search = '';

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['search'], 'required'],
      [['search'], 'string', 'max' => Offer::MAX_LENGTH_TICKET_NAME],
    ];
  }

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function attributeLabels(): array
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
