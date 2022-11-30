<?php

namespace app\models\forms;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;
use app\models\User;
use app\models\exceptions\FileExistException;
use app\models\exceptions\DataSaveException;

class RegistrationForm extends Model
{

  public string $name = '';
  public string $email = '';
  public string $avatar = '';
  public string $password = '';
  public string $passwordRepeat = '';


  // public string $userName = '';
  // public string $userEmail = '';
  // public string $avatar = '';
  //public string $userPassword = '';
  //public string $userPasswordAgain = '';

  /**
   * @inheritDoc
   */
  public function rules(): array
  {
    return [
      [['name', 'email', 'password', 'passwordRepeat'], 'required'],
      [['name'], 'string', 'max' => User::MAX_LENGTH_USERNAME],
      [['email'], 'string', 'max' => User::MAX_LENGTH_FILD],
      [['password', 'passwordRepeat'], 'string', 'min' => User::MIN_LENGTH_PASSWORD, 'max' => User::MAX_LENGTH_PASSWORD],
      [['passwordRepeat'], 'compare', 'compareAttribute' => 'password', 'message' => "Пароли не совпадают"],
      [['email'], 'email'],
      [['email'], 'unique', 'targetClass' => User::class, 'targetAttribute' => ['email' => 'email'], 'message' => 'Пользователь с таким e-mail уже существует'],
      [['avatar'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => true, 'extensions' => 'jpg, png', 'wrongExtension' => 'Только форматы jpg и png'],
    ];
  }

  /**
   * @inheritDoc
   */
  public function attributeLabels()
  {
    return [
      'name' => 'Ваше имя и фамилия',
      'email' => 'Email',
      'password' => 'Пароль',
      'passwordRepeat' => 'Повтор пароля',
      'avatar' => 'Загрузить аватар',
    ];
  }
  /**
   * Метод сохранения данных из формы настройки профиля пользователя в БД
   *
   * @param User $user - объект класса User
   * @throws DataSaveException
   * @throws FileExistException
   */
  public function createUser(): bool
  {
    $user = new User;

    $avatar = UploadedFile::getInstance($this, 'avatar');
    $this->avatar = $avatar;

    if (!$this->uploadAvatar($user, $avatar) && $this->avatar) {
      throw new FileExistException('Загрузить файл не удалось');
    }

    $user->name = $this->name;
    $user->email = $this->email;
    $user->password = $this->password;

    $transaction = Yii::$app->db->beginTransaction();

    try {
      $user->save();

      $auth = Yii::$app->authManager;
      $userRole = $auth->getRole(User::ROLE_USER);
      $auth->assign($userRole, $user->getId());

      $transaction->commit();
    } catch (DataSaveException $exception) {
      $transaction->rollback();
      throw new DataSaveException($exception->getMessage());
    }
    return true;
  }

  /**
   * Метод загрузки аватара пользователя в БД
   *
   * @param User $user - объект класса User
   * @param UploadedFile $avatar - объект класса UploadedFile
   * @return bool
   * @throws DataSaveException
   */
  public function uploadAvatar($user, $avatar): bool
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
