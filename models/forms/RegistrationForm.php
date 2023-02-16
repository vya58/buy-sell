<?php

namespace app\models\forms;

use app\models\User;
use app\src\exceptions\DataSaveException;
use app\src\exceptions\FileExistException;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class RegistrationForm extends Model
{
  public string $name = '';
  public string $email = '';
  public string $avatar = '';
  public string $password = '';
  public string $passwordRepeat = '';

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['name', 'email', 'password', 'passwordRepeat'], 'required', 'message' => 'Обязательное поле'],
      [['name'], 'string', 'max' => User::MAX_LENGTH_USERNAME],
      [['email'], 'string', 'max' => User::MAX_LENGTH_FILD],
      [['password', 'passwordRepeat'], 'string', 'min' => User::MIN_LENGTH_PASSWORD, 'max' => User::MAX_LENGTH_PASSWORD],
      [['name'], 'match', 'pattern' => '/^[A-zА-я\s]+$/u', 'message' => 'Не должно быть цифр и специальных символов'],
      [['passwordRepeat'], 'compare', 'compareAttribute' => 'password', 'message' => 'Пароли не совпадают'],
      [['email'], 'email', 'message' => 'Неверный email'],
      [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email'], 'message' => 'Пользователь с таким e-mail уже существует'],
      [['avatar'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => true, 'extensions' => 'jpg, png', 'wrongExtension' => 'Только форматы jpg и png'],
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
      'name' => 'Ваше имя и фамилия',
      'email' => 'Эл. почта',
      'password' => 'Пароль',
      'passwordRepeat' => 'Повтор пароля',
      'avatar' => 'Загрузить аватар',
    ];
  }
  /**
   * Метод сохранения данных из формы настройки профиля пользователя в БД
   *
   * @throws DataSaveException
   * @throws FileExistException
   */
  public function createUser(): bool
  {
    $user = new User;
    $avatar = UploadedFile::getInstance($this, 'avatar');

    if (!$avatar) {
      $avatar = '';
    }

    $this->avatar = $avatar;

    if ($this->validate()) {
      if (!$this->uploadAvatar($user, $avatar) && $this->avatar) {
        throw new FileExistException('Загрузить файл не удалось');
      }

      $user->name = $this->name;
      $user->email = $this->email;
      $user->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);

      $transaction = Yii::$app->db->beginTransaction();

      try {
        if ($user->save()) {
          $auth = Yii::$app->authManager;
          $userRole = $auth->getRole(User::ROLE_USER);
          $auth->assign($userRole, $user->getId());
        }
        $transaction->commit();
      } catch (DataSaveException $exception) {
        $transaction->rollback();
        throw new DataSaveException($exception->getMessage('Ошибка регистрации'));
      }
      return true;
    }
    return false;
  }

  /**
   * Метод загрузки аватара пользователя в БД
   *
   * @param User $user - объект класса User
   * @param UploadedFile $avatar - объект класса UploadedFile
   *
   * @return bool
   * @throws DataSaveException
   */
  public function uploadAvatar(User $user, UploadedFile $avatar): bool
  {
    if ($this->validate() && $this->avatar) {
      // Уникальное имя файла в БД
      $addedAvatarName = md5(microtime(true)) . '.' . $avatar->getExtension();
      $user->avatar = $addedAvatarName;

      if (!$avatar->saveAs('@webroot/' . User::USER_AVATAR_UPLOAD_PATH . $addedAvatarName)) {
        throw new DataSaveException('Ошибка загрузки аватара');
      }
      return true;
    }
    return false;
  }
}
