<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\models\Comment;
use app\models\User;
use app\models\ChatFirebase;
use app\models\forms\ChatForm;
use app\models\FireDatabase;
use Kreait\Firebase\Factory;

class ChatController extends Controller
{
  public function actionIndex()
  {
    $offerId = 1;
    $ownerOfferId = 20;
    $buyerId = 33;
    $userId = Yii::$app->user->id;
    $buyerName = User::findOne($buyerId)->name;

    //$message = 'Привет!';
    $chatFirebase = new ChatFirebase($offerId, $buyerId);
    $chatForm = new ChatForm();
    //\yii\helpers\VarDumper::dump(Yii::$app->request->isPjax, 3, true);

    if ($chatForm->load(Yii::$app->request->post())) {
      //\yii\helpers\VarDumper::dump($chatFirebase, 3, true);
      $messages = $chatFirebase->getValueChat();
      $chatForm->addMessage($chatFirebase, $offerId, $ownerOfferId, $buyerId);
      return $this->redirect('/chat/index');
    }

    if (Yii::$app->request->isAjax) {
      //\yii\helpers\VarDumper::dump(Yii::$app->request->isPjax, 3, true);
    }

    if ($chatFirebase->getValueChat()) {
    }

    //$newMessage = $chatFirebase->sendMessage($userId, $message);
    $messages = $chatFirebase->getValueChat();

    //$text = 'Ушло!';

    //\yii\helpers\VarDumper::dump($buyerName, 3, true);
    return $this->render('index', compact('chatForm', 'messages', 'buyerName'));
  }
}
