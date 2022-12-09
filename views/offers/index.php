<?php

/** @var yii\web\View $this */

use \yii\helpers\Url;

?>

<section class="ticket">
  <div class="ticket__wrapper">
    <h1 class="visually-hidden">Карточка объявления</h1>
    <div class="ticket__content">
      <div class="ticket__img">
        <img src="img/ticket.jpg" srcset="img/ticket@2x.jpg 2x" alt="Изображение товара">
      </div>
      <div class="ticket__info">
        <h2 class="ticket__title">Мое старое кресло</h2>
        <div class="ticket__header">
          <p class="ticket__price"><span class="js-sum">4000</span> ₽</p>
          <p class="ticket__action">ПРОДАМ</p>
        </div>
        <div class="ticket__desc">
          <p>Продам свое старое кресло, чтобы сидеть и читать книги зимними вечерами. Ножки мягкие, мой пол не царапают. Кресло почти новое &ndash; продаю, т.к. надоел серый цвет. Можно, конечно, накинуть плед и спасти ситуацию, но я все-таки хочу просто другое кресло. В общем оно на самом деле удобное и с ним все хорошо, просто нам пора расстаться.</p>
        </div>
        <div class="ticket__data">
          <p>
            <b>Дата добавления:</b>
            <span>20 ноября 2019</span>
          </p>
          <p>
            <b>Автор:</b>
            <a href="#">Денис Шкатулкин</a>
          </p>
          <p>
            <b>Контакты:</b>
            <a href="mailto:shkatulkin@ya.ru">shkatulkin@ya.ru</a>
          </p>
        </div>
        <ul class="ticket__tags">
          <li>
            <a href="#" class="category-tile category-tile--small">
              <span class="category-tile__image">
                <img src="img/cat.jpg" srcset="img/cat@2x.jpg 2x" alt="Иконка категории">
              </span>
              <span class="category-tile__label">Дом</span>
            </a>
          </li>
          <li>
            <a href="#" class="category-tile category-tile--small">
              <span class="category-tile__image">
                <img src="img/cat04.jpg" srcset="img/cat04@2x.jpg 2x" alt="Иконка категории">
              </span>
              <span class="category-tile__label">Спорт и отдых</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="ticket__comments">
      <div class="ticket__warning">
        <?php if (Yii::$app->user->isGuest) : ?>
          <p>Отправка комментариев доступна <br>только для зарегистрированных пользователей.</p>
          <a href="<?= Url::to('registration') ?>" class="message__link btn btn--big">Вход и регистрация</a>
        <?php endif; ?>
      </div>
      <h2 class="ticket__subtitle">Коментарии</h2>
      <?php if (!Yii::$app->user->isGuest) : ?>
        <div class="ticket__comment-form">
          <form action="#" method="post" class="form comment-form">
            <div class="comment-form__header">
              <a href="#" class="comment-form__avatar avatar">
                <img src="<?= file_exists(Yii::$app->request->baseUrl . 'uploads/avatars/' . Yii::$app->user->identity->avatar) ? Yii::$app->request->baseUrl . 'uploads/avatars/' . Yii::$app->user->identity->avatar : 'img/avatar.jpg' ?>" srcset="<?= file_exists(Yii::$app->request->baseUrl . 'uploads/avatars/' . Yii::$app->user->identity->avatar) ? '' : 'img/avatar@2x.jpg 2x' ?>" alt="Аватар пользователя">
              </a>
              <p class="comment-form__author">Вам слово</p>
            </div>
            <div class="comment-form__field">
              <div class="form__field">
                <textarea name="comment" id="comment-field" cols="30" rows="10" class="js-field">Нормальное вообще кресло! А как насч</textarea>
                <label for="comment-field">Текст комментария</label>
                <span>Обязательное поле</span>
              </div>
            </div>
            <button class="comment-form__button btn btn--white js-button" type="submit" disabled="">Отправить</button>
          </form>
        </div>
      <?php endif; ?>
      <?php if ($comments) : ?>
        <div class="ticket__comments-list">
          <ul class="comments-list">
            <li>
              <div class="comment-card">
                <div class="comment-card__header">
                  <a href="#" class="comment-card__avatar avatar">
                    <img src="img/avatar02.jpg" srcset="img/avatar02@2x.jpg 2x" alt="Аватар пользователя">
                  </a>
                  <p class="comment-card__author">Георгий Шпиц</p>
                </div>
                <div class="comment-card__content">
                  <p>Что это за рухлядь? Стыдно такое даже фотографировать, не то, что&nbsp;продавать.</p>
                </div>
              </div>
            </li>
            <li>
              <div class="comment-card">
                <div class="comment-card__header">
                  <a href="#" class="comment-card__avatar avatar">
                    <img src="img/avatar03.jpg" srcset="img/avatar03@2x.jpg 2x" alt="Аватар пользователя">
                  </a>
                  <p class="comment-card__author">Александр Бурый</p>
                </div>
                <div class="comment-card__content">
                  <p>А можете доставить мне домой? Готов доплатить 300 сверху. <br>Живу в центре прямо рядом с Моховой улицей. Готов купить прямо сейчас. Мой телефон 9032594748</p>
                </div>
              </div>
            </li>
          </ul>
        </div>
      <?php else : ?>
        <div class="ticket__message">
          <p>У этой публикации еще нет ни одного комментария.</p>
        </div>
      <?php endif; ?>
    </div>
    <button class="chat-button" type="button" aria-label="Открыть окно чата"></button>
  </div>
</section>

<section class="chat visually-hidden">
  <h2 class="chat__subtitle">Чат с продавцом</h2>
  <ul class="chat__conversation">
    <li class="chat__message">
      <div class="chat__message-title">
        <span class="chat__message-author">Вы</span>
        <time class="chat__message-time" datetime="2021-11-18T21:15">21:15</time>
      </div>
      <div class="chat__message-content">
        <p>Добрый день!</p>
        <p>Какова ширина кресла? Из какого оно материала?</p>
      </div>
    </li>
    <li class="chat__message">
      <div class="chat__message-title">
        <span class="chat__message-author">Продавец</span>
        <time class="chat__message-time" datetime="2021-11-18T21:21">21:21</time>
      </div>
      <div class="chat__message-content">
        <p>Добрый день!</p>
        <p>Ширина кресла 59 см, это хлопковая ткань. кресло очень удобное, и почти новое, без сколов и прочих дефектов</p>
      </div>
    </li>
  </ul>
  <form class="chat__form">
    <label class="visually-hidden" for="chat-field">Ваше сообщение в чат</label>
    <textarea class="chat__form-message" name="chat-message" id="chat-field" placeholder="Ваше сообщение"></textarea>
    <button class="chat__form-button" type="submit" aria-label="Отправить сообщение в чат"></button>
  </form>
</section>
