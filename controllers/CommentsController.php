<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Comment;
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
    if (Yii::$app->user->isGuest) {
      throw new NotFoundHttpException();
    }

    $offers = Offer::getWithNewCommentsOffers($id);

    return $this->render(
      'index',
      [
        'offers' => $offers,
      ]
    );
  }

  /**
   * Страница просмотра комментариев к объявлениям пользователя
   *
   * @param int $id - id пользователя
   * @return Response|string - код страницы просмотра страницы комментариев
   * @throws NotFoundHttpException
   */
  public function actionRemove($commentId): Response|string
  {
    $comment = Comment::find()
      ->with('offers')
      ->where(['comment_id' => $commentId])
      ->one();

    $offer = $comment->offers;
    $ownerId = $offer[0]['owner_id'];

    // Если пользователь не обладает правом редактирования объявления (не модератор и не автор объявления),
    // то он переадресуется на страницу просмотра объявления без удаления комментария
    if (\Yii::$app->user->can('updateOwnContent', ['resource' => $comment]) || \Yii::$app->user->can('updateOwnContent', ['resource' => $offer[0]])) {
       $comment->delete();
    }
    return $this->redirect(['comments/index', 'id' => $ownerId]);
  }
}
