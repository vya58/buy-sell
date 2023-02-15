<?php

use yii\helpers\Html;

?>

<?php if (mb_strlen($text) > $textLength) : ?>
  <?= Html::encode(mb_substr($text, 0, $textLength) . '...') ?>
<?php else : ?>
  <?= Html::encode($text) ?>
<?php endif; ?>