<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use app\models\forms\LoginForm;
use app\models\User;
use yii\authclient\AuthAction;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;
use yii\web\ServerErrorHttpException;


class LoginController extends Controller
{
  /**
   * {@inheritdoc}
   */
  /*
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    return $this->redirect(['tasks/index']);
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
*/
  /**
   * Страница входа.
   *
   *
   *
   *
   * @return Response|string
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

    return $this->render(
      'index',
      [
        'loginForm' => $loginForm,
      ]
    );
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
   * @param $client - социальная сеть, через которую происходит авторизация
   *
   * @return Response
   */
  public function onAuthSuccess(ClientInterface $client)
  {
    $attributes = $client->getUserAttributes();

    $email = ArrayHelper::getValue($attributes, 'email');

    if (!$email) {
      throw new BadRequestHttpException('Email отсутствует');
    }
    // Пытаемся найти пользователя в базе по почте из соц. сети
    $user = User::findOne(['email' => $email]);

    if (!$user) {
      $user = new User;
      $user->createVkUser($attributes);
    }

    Yii::$app->user->login($user);

    return $this->goHome();
  }
}
