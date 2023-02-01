<?php

namespace app\controllers;

use app\models\Category;
use Yii;
//use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\forms\LoginForm;
use app\models\OfferCategory;
use app\models\Offer;
use app\models\forms\OfferSearchForm;
use app\models\OfferComment;
use \yii\db\ActiveQuery;
use yii\widgets\ActiveForm;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;
use app\models\User;
use yii\authclient\AuthAction;
use yii\data\ActiveDataProvider;
use yii\grid\GridView;


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
    // Категории для section class="categories-list"
    $offerCategories = OfferCategory::find()
      ->with('category')
      ->all();

    // Самые новые предложения
    $newOffersdataProvider = new ActiveDataProvider([
      'query' => Offer::getNewOffers(),
      'pagination' => [
        'pageSize' => Yii::$app->params['newOffersCount'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);
    // Самые обсуждаемые предложения
    $mostTalkedOffers = Offer::getMostTalkedOffers();

    return $this->render('index', compact('offerCategories', 'newOffersdataProvider', 'mostTalkedOffers'));
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

      return $this->render('error', compact('statusCode', 'message'));
    }
    return $this->render('error', compact('statusCode', 'message'));
  }

  /**
   * Страница результатов поиска объявлений
   *
   * @return string
   */
  public function actionSearch()
  {
    $foundOffers = false;
    $model = new OfferSearchForm();

    if (Yii::$app->request->getIsGet()) {
      $search = Yii::$app->request->get();
      $model->load($search);
      $query = $model->search;
      $this->view->params['query'] = $query;
      $foundOffers = Offer::searchOffers($query);
    }

    $dataProvider = new ActiveDataProvider([
      'query' => $foundOffers,
      'pagination' => [
        'pageSize' => Yii::$app->params['pageSize'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);

    // Самые новые предложения
    $newOffersdataProvider = new ActiveDataProvider([
      'query' => Offer::getNewOffers(),
      'pagination' => [
        'pageSize' => Yii::$app->params['newOffersCount'],
      ],
      'sort' => [
        'defaultOrder' => [
          'offer_date_create' => SORT_DESC,
        ]
      ],
    ]);

    return $this->render('search', compact('newOffersdataProvider', 'dataProvider'));
  }
}
