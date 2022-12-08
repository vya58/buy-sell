<?php

namespace app\controllers;

use Yii;
//use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\ContactForm;
use yii\widgets\ActiveForm;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\authclient\AuthAction;


class SiteController extends Controller
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
        'only' => ['logout'],
        'rules' => [
          [
            'actions' => ['logout'],
            'allow' => true,
            'roles' => ['@'],
          ],
        ],
      ],
      'verbs' => [
        'class' => VerbFilter::class,
        'actions' => [
          'logout' => ['post'],
        ],
      ],
    ];
  }
*/
  /*
public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'logout', 'signup', 'registration'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['*'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }
    */

  /**
   * Displays homepage.
   *
   * @return string
   */
  public function actionIndex()
  {
    //Временная переменная для подключения статичных вариантов Главной страницы.
    $data = false;

    return $this->render(
      'index',
      [
        //'dataProvider' => $dataProvider,
        //'categories' => $categories,
        'data' => $data
      ]
    );
  }

  /**
   * Logout action.
   *
   * @return Response
   */

  public function actionLogout()
  {
    Yii::$app->user->logout();

    return $this->goHome();
  }

  public function actionError()
  {
    $this->layout = 'error';
    $this->view->params['htmlClass'] = 'html-not-found';
    $this->view->params['bodyClass'] = 'body-not-found';
    $exception = Yii::$app->errorHandler->exception;
    $statusCode = $exception->statusCode;
    $message = 'Страница не найдена';

    if ($exception->statusCode >= 500) {
      $this->view->params['htmlClass'] = 'html-server';
      $this->view->params['bodyClass'] = 'body-server';
      $message = 'Ошибка cервера';

      return $this->render(
        'error',
        [
          'statusCode' => $statusCode,
          'message' => $message,
        ]
      );
    }

    return $this->render(
      'error',
      [
        'statusCode' => $statusCode,
        'message' => $message,
      ]
    );
  }
}
