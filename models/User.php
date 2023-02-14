<?php

namespace app\models;

use Yii;
use \yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $user_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $avatar
 * @property string $date_add
 *
 * @property Auth[] $auths
 * @property Comment[] $comments
 * @property Offer[] $offers
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
   *
   * @return string
   */
  public static function tableName(): string
  {
    return 'user';
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['name', 'email', 'password'], 'required'],
      [['date_add'], 'safe'],
      [['name'], 'string', 'max' => self::MAX_LENGTH_USERNAME],
      [['password', 'passwordRepeat'], 'string', 'min' => self::MIN_LENGTH_PASSWORD, 'max' => self::MAX_LENGTH_PASSWORD],
      [['email', 'avatar'], 'string', 'max' => self::MAX_LENGTH_FILD],
      [['email'], 'unique'],
      [['avatar'], 'unique'],
    ];
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   */
  public function attributeLabels(): array
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

  /**
   * Gets query for [[Auths]].
   *
   * @return ActiveQuery
   */
  public function getAuths(): ActiveQuery
  {
    return $this->hasMany(Auth::class, ['user_id' => 'user_id']);
  }

  /**
   * Gets query for [[Comments]].
   *
   * @return ActiveQuery
   */
  public function getComments(): ActiveQuery
  {
    return $this->hasMany(Comment::class, ['owner_id' => 'user_id']);
  }

  /**
   * Gets query for [[Offers]].
   *
   * @return ActiveQuery
   */
  public function getOffers(): ActiveQuery
  {
    return $this->hasMany(Offer::class, ['owner_id' => 'user_id']);
  }

  /**
   * Validates password
   *
   * @param string $password password to validate
   * @return bool if password provided is valid for current user
   */
  public function validatePassword(string $password): bool
  {
    return Yii::$app->security->validatePassword($password, $this->password);
  }

  /**
   * Создание нового пользователя через ВКонтакте
   * @param array $userAttributes - атрибуты пользователя переданные ВКонтакте
   */
  public function createVkUser(array $userAttributes): void
  {
    $this->name = $userAttributes['first_name'] . $userAttributes['last_name'];
    $this->email = $userAttributes['email'];
    // Присваиваем рандомный пароль пользователю для заполнения обязательного поля 'password' в таблиwе 'user'
    // Сохраняется только его хеш, но не сам пароль, т.к. по условию ТЗ 'пользователь, зарегистрированный через ВК, не имеет пароля, а значит не может поменять его'
    $this->password = Yii::$app->getSecurity()->generatePasswordHash(md5(microtime(true)));

    if ($this->save()) {
      $auth = Yii::$app->authManager;
      $userRole = $auth->getRole(User::ROLE_USER);
      $auth->assign($userRole, $this->getId());
    }
  }
}
