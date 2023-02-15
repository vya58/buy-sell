<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\User;
use Yii;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;


class LoginController extends Controller
{
  /**
   * {@inheritdoc}
   */
  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        'denyCallback' => function () {
          return $this->redirect(['site/index']);
        },
        'only' => ['index'],
        'rules' => [
          [
            'allow' => true,
            'actions' => ['index'],
            'roles' => ['?'],
          ],
        ]
      ]
    ];
  }

  /**
   * Страница входа.
   *
   */
  public function actionIndex()
  {
    $loginForm = new LoginForm();

    if (Yii::$app->request->getIsPost()) {
      $loginForm->load(Yii::$app->request->post());

      if ($loginForm->validate()) {
        $user = $loginForm->getUser();
        Yii::$app->user->login($user);

        return $this->goHome();
      }
    }
    $loginForm->password = null;

    return $this->render('index', compact('loginForm'));
  }

  /**
   * Авторизация в социальной сети
   *
   * @return array
   */
  public function actions(): array
  {
    return [
      'auth' => [
        'class' => AuthAction::class,
        'successCallback' => [$this, 'onAuthSuccess'],
      ],
    ];
  }

  /**
   * Результат успешной регистрации с помощью социальной сети
   *
   * @param ClientInterface $client - социальная сеть, через которую происходит авторизация
   *
   * @throws BadRequestHttpException
   */
  public function onAuthSuccess(ClientInterface $client)
  {
    $attributes = $client->getUserAttributes();

    $email = ArrayHelper::getValue($attributes, 'email');

    if (!$email) {
      throw new BadRequestHttpException('Email отсутствует');
    }
    // Пытаемся найти пользователя в базе по почте из соц. сети
    $user = User::findOne(['email' => (string) $email]);

    if (!$user) {
      $user = new User;
      $user->createVkUser($attributes);
    }

    Yii::$app->user->login($user);

    return $this->goHome();
  }
}
