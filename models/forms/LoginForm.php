<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;

use app\models\User;
use yii\web\BadRequestHttpException;
use app\models\exceptions\FileExistException;
use app\models\exceptions\DataSaveException;

class LoginForm extends Model
{
  public $email;
  public $password;

  private $_user;

  /**
   * @inheritDoc
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
   */
  public function attributeLabels()
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
   */
  public function validatePassword($attribute)
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
  public function login()
  {
    if ($this->validate()) {
      return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
    }
    return false;
  }
}
