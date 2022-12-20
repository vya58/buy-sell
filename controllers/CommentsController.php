<?php

namespace app\controllers;

use Yii;
use \yii\db\ActiveQuery;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Offer;

class CommentsController extends Controller
{
  /**
   * {@inheritdoc}
   */

  public function behaviors()
  {
    return [
      'access' => [
        'class' => AccessControl::class,
        /*
        'denyCallback' => function () {
            return $this->redirect(['login/index']);
        },
        */
        'rules' => [
          [
            'allow' => true,
            'roles' => ['@']
          ]
        ],
      ]
    ];
  }

  /**
   * Страница просмотра комментариев к объявлениям пользователя
   *
   * @param int $id - id пользователя
   * @return Response|string - код страницы просмотра страницы комментариев
   * @throws NotFoundHttpException
   */
  public function actionIndex(int $id): Response|string
  {
    if (!$id) {
      throw new NotFoundHttpException();
    }

    $offers = Offer::find()
      ->with('owner', 'comments')
      ->joinWith(
        [
          'offerComments' => function (ActiveQuery $query) {
            $query->orderBy('offer_comment.id DESC');
          }
        ],
        true,
        'INNER JOIN'
      )
      ->where(['owner_id' => $id])
      ->all();

    return $this->render(
      'index',
      [
        'offers' => $offers,
      ]
    );
  }
}
