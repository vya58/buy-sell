<?php

namespace app\controllers;

use Yii;
use app\models\forms\RegistrationForm;
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
   * @return string cтраница с формой регистрации
   */
  public function actionIndex()
  {
    $registrationForm = new RegistrationForm();

    if (Yii::$app->request->getIsPost()) {
      $registrationForm->load(Yii::$app->request->post());

      if ($registrationForm->createUser()) {
        return $this->goHome();
      }
    }
    return $this->render('index', compact('registrationForm'));
  }
}
