<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 * @property string $date_add
 */
class User extends \yii\db\ActiveRecord
{
  // Роли пользователя
  public const ROLE_MODERATOR = 'moderator';
  public const ROLE_USER = 'user';

  /**
   * {@inheritdoc}
   */
  public static function tableName()
  {
    return 'user';
  }

  /**
   * {@inheritdoc}
   */
  public function rules()
  {
    return [
      [['name', 'email', 'password'], 'required'],
      [['date_add'], 'safe'],
      [['name'], 'string', 'max' => 50],
      [['email', 'password', 'avatar'], 'string', 'max' => 255],
      [['email'], 'unique'],
      [['avatar'], 'unique'],
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function attributeLabels()
  {
    return [
      'user_id' => 'ID пользователя',
      'name' => 'Имя и Фамилия пользователя',
      'email' => 'Email пользователя',
      'password' => 'Пароль пользователя',
      'avatar' => 'Аватар пользователя',
      'date_add' => 'Дата добавления пользователя',
    ];
  }
}
