<?php

namespace app\controllers;

use app\models\forms\RegistrationForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class RegistrationController extends Controller
{
  /**
   * @inheritDoc
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
            'roles' => ['?']
          ]
        ]
      ]
    ];
  }

  /**
   * Страница с формой регистрации нового пользователя
   *
   */
  public function actionIndex()
  {
    $registrationForm = new RegistrationForm();

    if (Yii::$app->request->getIsPost()) {
      $registrationForm->load(Yii::$app->request->post());

      if ($registrationForm->createUser()) {
        return $this->redirect('login');
      }
    }
    return $this->render('index', compact('registrationForm'));
  }
}
