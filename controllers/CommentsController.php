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
   * @param int $id - id пользователя
   * @return string - результат рендеринга страницы просмотра страницы комментариев
   */
  public function actionIndex(int $id): Response|string
  {
    $offers = Offer::getWithNewCommentsOffers($id);

    return $this->render('index', compact('offers'));
  }

  /**
   * Удаление комментария к объявлению пользователя
   *
   * @param int $commentId - id комментария
   * @return Response - Объект Response
   */
  public function actionRemove($commentId): Response
  {
    $comment = Comment::find()
      ->with('offer')
      ->where(['comment_id' => $commentId])
      ->one();

    if ($comment) {
      $offer = $comment->offer;
      $ownerId = $offer->owner_id;

      // Если пользователь не обладает правом редактирования объявления (не модератор и не автор объявления),
      // то в случае попытки удаления, сервер возвращает код 403 без удаления комментария
      if (!\Yii::$app->user->can('updateOwnContent', ['resource' => $comment]) || !\Yii::$app->user->can('updateOwnContent', ['resource' => $offer])) {
        throw new ForbiddenHttpException();
      }
      $comment->delete();
    }
    return $this->redirect(['comments/', 'id' => $ownerId]);
  }
}
