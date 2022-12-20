<?php

/** @var yii\web\View $this */

use yii\helpers\Html;
use \yii\helpers\Url;

?>

<section class="comments">
  <div class="comments__wrapper">
    <?= !$offers ? Html::tag('p', 'У ваших публикаций еще нет комментариев.', ['class' => 'comments__message']) : '' ?>
    <h1 class="visually-hidden">Страница комментариев</h1>
    <?php foreach ($offers as $offer) : ?>
      <div class="comments__block">
        <div class="comments__header">
          <a href="#" class="announce-card">
            <h2 class="announce-card__title"><?= Html::encode($offer->offer_title) ?></h2>
            <span class="announce-card__info">
              <span class="announce-card__price">₽ <?= Html::encode($offer->offer_price) ?></span>
              <span class="announce-card__type"><?= Html::encode($offer->offer_type) ?></span>
            </span>
          </a>
        </div>
        <ul class="comments-list">
          <?php $comments = $offer->comments
          ?>
          <?php foreach ($comments as $comment) :
          ?>
            <li class="js-card">
              <div class="comment-card">
                <div class="comment-card__header">
                  <a href="#" class="comment-card__avatar avatar">
                    <img src="<?= $comment->owner->avatar ? Html::encode('/uploads/avatars/' . $comment->owner->avatar) : '/img/avatar.jpg' ?>" srcset="<?= $comment->owner->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
                  </a>
                  <p class="comment-card__author"><?= Html::encode($comment->owner->name) ?></p>
                </div>
                <div class="comment-card__content">
                  <p><?= Html::encode($comment->comment_text) ?></p>
                </div>
                <button class="comment-card__delete js-delete" type="button">Удалить</button>
              </div>
            </li>
          <?php endforeach;
          ?>
        </ul>
      </div>
    <?php endforeach; ?>
  </div>
</section>
