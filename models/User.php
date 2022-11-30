<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $password
 *
 * @property string|null $avatar
 * @property string $date_add
 */
class User extends ActiveRecord implements IdentityInterface
{
  // Роли пользователя
  public const ROLE_MODERATOR = 'moderator';
  public const ROLE_USER = 'user';

  public const MAX_LENGTH_USERNAME = 50;
  public const MAX_LENGTH_FILD = 255;
  public const MIN_LENGTH_PASSWORD = 6;
  public const MAX_LENGTH_PASSWORD = 64;
  public const USER_AVATAR_UPLOAD_PATH = '/uploads/avatars/';
  public $passwordRepeat;


  /**
   * {@inheritdoc}
   */
  public static function findIdentity($id)
  {
    return self::findOne($id);
  }

  /**
   * {@inheritdoc}
   */
  public static function findIdentityByAccessToken($token, $type = null)
  {
    // TODO: Implement findIdentityByAccessToken() method.
  }

  /**
   * {@inheritdoc}
   */
  public function getId()
  {
    return $this->getPrimaryKey();
  }

  /**
   * {@inheritdoc}
   */
  public function getAuthKey()
  {
    // TODO: Implement getAuthKey() method.
  }

  /**
   * {@inheritdoc}
   */
  public function validateAuthKey($authKey)
  {
    // TODO: Implement validateAuthKey() method.
  }

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
      [['name', 'avatar'], 'string', 'max' => self::MAX_LENGTH_USERNAME],
      [['password', 'passwordRepeat'], 'string', 'min' => self::MIN_LENGTH_PASSWORD, 'max' => self::MAX_LENGTH_PASSWORD],
      [['email', 'avatar'], 'string', 'max' => self::MAX_LENGTH_FILD],
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
