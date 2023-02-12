<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Offer;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

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
        'denyCallback' => function () {
          return $this->redirect(['/login']);
        },
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
   * @param int $userId - id пользователя
   * @return Response|string - код страницы просмотра страницы комментариев
   */
  public function actionIndex(int $userId): Response|string
  {
    $offers = Offer::getWithNewCommentsOffers($userId);

    return $this->render('index', compact('offers'));
  }

  /**
   * Удаление комментария к объявлению пользователя
   *
   * @param int $commentId - id комментария
   * @return Response|string - код страницы просмотра страницы комментариев
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
    // то в случае попытки удаления, сервер возвращает код 403 без удаления комментария
    if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $comment]) || !\Yii::$app->user->can('updateOwnContent', ['resource' => $offer[0]])) {
      throw new ForbiddenHttpException();
    }

    $comment->delete();

    return $this->redirect(['comments/', 'id' => $ownerId]);
  }
}
