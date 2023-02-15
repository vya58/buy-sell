<?php

use yii\helpers\Html;

?>

<time class="chat__message-time" datetime="2021-11-18T21:15"><?= Yii::$app->formatter->asDate($date, 'php:j F Y') === Yii::$app->formatter->asDate('now', 'php:j F Y') ? Html::encode(Yii::$app->formatter->asDate($date, 'php:H:i')) : Html::encode(Yii::$app->formatter->asDate($date, 'php:j F Y H:i')) ?></time>