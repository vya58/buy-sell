<?php

/** @var yii\web\View $this */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

?>

<section class="comments">
  <div class="comments__wrapper">
    <?= !count($offers) ? Html::tag('p', 'У ваших публикаций еще нет комментариев.', ['class' => 'comments__message']) : '' ?>
    <h1 class="visually-hidden">Страница комментариев</h1>
    <?php if (count($offers)) : ?>
      <?php foreach ($offers as $offer) : ?>
        <div class="comments__block">
          <div class="comments__header">
            <a href="<?= isset($offer->offer_id) ? Url::to(['offers/index', 'id' => $offer->offer_id]) : '#' ?>" class="announce-card">
              <h2 class="announce-card__title"><?= isset($offer->offer_title) ? Html::encode($offer->offer_title) : '' ?></h2>
              <span class="announce-card__info">
                <span class="announce-card__price">₽ <?= isset($offer->offer_price) ? Html::encode($offer->offer_price) : '' ?></span>
                <span class="announce-card__type"><?= isset($offer->offer_type) ? Html::encode($offer->offer_type) : '' ?></span>
              </span>
            </a>
          </div>

          <ul class="comments-list">
            <?php if (isset($offer->comments)) : ?>
              <?php
              $comments = $offer->comments;
              ArrayHelper::multisort($comments, ['comment_id'], [SORT_DESC]);
              ?>
              <?php foreach ($comments as $comment) : ?>
                <li class="js-card">
                  <div class="comment-card">
                    <div class="comment-card__header">
                      <a href="#" class="comment-card__avatar avatar">
                        <img src="<?= isset($comment->owner->avatar) ? Html::encode('/uploads/avatars/' . $comment->owner->avatar) : '/img/avatar.jpg' ?>" srcset="<?= $comment->owner->avatar ? '' : '/img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
                      </a>
                      <p class="comment-card__author"><?= isset($comment->owner->name) ? Html::encode($comment->owner->name) : '' ?></p>
                    </div>
                    <div class="comment-card__content">
                      <?= isset($comment->comment_text) ? Html::tag('p', Html::encode($comment->comment_text), ['style' => ['word-wrap' => 'break-word']]) : Html::tag('p', '', ['style' => ['word-wrap' => 'break-word']]) ?>
                    </div>
                    <!-- Кнопка "Удалить" появляется только для владельца комментария, владельца объявления и модератора -->
                    <?= (isset($comment->comment_id) && ($comment->owner->user_id === \Yii::$app->user->id || \Yii::$app->user->can('moderator') || \Yii::$app->user->id === $offer->owner_id)) ? Html::button('Удалить', [
                      'class' => 'comment-card__delete js-delete',
                      'onclick' => 'window.location.href = "' . Url::to(['/comments/remove', 'commentId' => $comment->comment_id]) . '";',
                    ]) : '' ?>
                  </div>
                </li>
              <?php endforeach; ?>
            <?php endif; ?>
          </ul>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
