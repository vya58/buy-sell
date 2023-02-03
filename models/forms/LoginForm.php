<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
  public $email;
  public $password;

  private $_user;

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['email', 'password'], 'required', 'message' => 'Обязательное поле'],
      [['email'], 'string', 'max' => User::MAX_LENGTH_FILD],
      [['password'], 'string', 'min' => User::MIN_LENGTH_PASSWORD, 'max' => User::MAX_LENGTH_PASSWORD],
      [['email'], 'email', 'message' => 'Неверный email'],
      ['password', 'validatePassword'],
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
      'email' => 'Эл. почта',
      'password' => 'Пароль',
    ];
  }

  /**
   * Метод валидации пароля при входе пользователя
   *
   * @param string $attribute - строка из поля 'password' формы входа
   *
   * @return void
   */
  public function validatePassword($attribute): void
  {
    if (!$this->hasErrors()) {
      $user = $this->getUser();

      if (!$user || !$user->validatePassword($this->password)) {
        $this->addError($attribute, 'Неправильный email или пароль');
      }
    }
  }

  /**
   * Метод получения данных пользователя по email
   *
   * @return User|null $user - объект класса User
   */
  public function getUser(): ?User
  {
    if (null === $this->_user) {
      $this->_user = User::findOne(['email' => $this->email]);
    }
    return $this->_user;
  }

  /**
   * Logs in a user using the provided username and password.
   * @return bool whether the user is logged in successfully
   */
  public function login(): bool
  {
    if ($this->validate()) {
      return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
    return false;
  }
}
