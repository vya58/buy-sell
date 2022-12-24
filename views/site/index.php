<?php

/** @var yii\web\View $this */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use \yii\helpers\Url;
use app\models\Offer;

?>
<?php if (!$data) : ?>
  <div class="message">
    <div class="message__text">
      <p>На сайте еще не опубликовано ни&nbsp;одного объявления.</p>
    </div>
    <?php if (Yii::$app->user->isGuest) : ?>
      <a href="<?= Url::to('/registration/index') ?>" class="message__link btn btn--big">Вход и регистрация</a>
    <?php endif; ?>
  </div>
<?php endif; ?>

<?php if ($data) : ?>
  <section class="categories-list">
    <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
    <ul class="categories-list__wrapper">
      <?php $categoryIds = []; ?>
      <?php foreach ($offerCategories as $offerCategory) : ?>
        <?php if (!ArrayHelper::isIn($offerCategory->category->category_id, $categoryIds)) : ?>
          <?php $countOffersInCategory = $offerCategory->getCountOffersInCategory($offerCategory->category->category_id); ?>
          <li class="categories-list__item">
            <a href="#" class="category-tile category-tile--default">
              <span class="category-tile__image">
                <img src="<?= Html::encode(Offer::OFFER_IMAGE_UPLOAD_PATH . Offer::getImageOfRandomOffers($offerCategory)) ?>" alt="Иконка категории">
              </span>
              <span class="category-tile__label"><?= Html::encode($offerCategory->category->category_name) ?> <span class="category-tile__qty js-qty"><?= Html::encode($countOffersInCategory) ?></span></span>
            </a>
          </li>
        <?php endif; ?>
        <?php $categoryIds[] = $offerCategory->category->category_id; ?>
      <?php endforeach; ?>
    </ul>
  </section>
  <section class="tickets-list">
    <h2 class="visually-hidden">Самые новые предложения</h2>
    <div class="tickets-list__wrapper">
      <div class="tickets-list__header">
        <p class="tickets-list__title">Самое свежее</p>
      </div>
      <ul>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color01">
            <div class="ticket-card__img">
              <img src="img/item01.jpg" srcset="img/item01@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">Куплю</span>
              <div class="ticket-card__categories">
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Монстера</a></h3>
                <p class="ticket-card__price"><span class="js-sum">1000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Куплю монстеру зеленую в хорошем зеленом состоянии, буду поливать...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color02">
            <div class="ticket-card__img">
              <img src="img/item02.jpg" srcset="img/item02@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Мое старое кресло</a></h3>
                <p class="ticket-card__price"><span class="js-sum">4000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Продам свое старое кресло, чтобы сидеть и читать книги зимними...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color03">
            <div class="ticket-card__img">
              <img src="img/item03.jpg" srcset="img/item03@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">ЭЛЕКТРОНИКА</a>
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Дедушкины часы</a></h3>
                <p class="ticket-card__price"><span class="js-sum">45 000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Продаю дедушкины часы в&nbsp;прекрасном состоянии, ходят до...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color04">
            <div class="ticket-card__img">
              <img src="img/item04.jpg" srcset="img/item04@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">Куплю</span>
              <div class="ticket-card__categories">
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Кофеварка</a></h3>
                <p class="ticket-card__price"><span class="js-sum">2000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Куплю вот такую итальянскую кофеварку, можно любой фирмы...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color05">
            <div class="ticket-card__img">
              <img src="img/item05.jpg" srcset="img/item05@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">Авто</a>
                <a href="#">ЭЛЕКТРОНИКА</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Ленд Ровер</a></h3>
                <p class="ticket-card__price"><span class="js-sum">900 000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Куплю монстеру зеленую в хорошем зеленом состоянии, буду поливать...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color06">
            <div class="ticket-card__img">
              <img src="img/item06.jpg" srcset="img/item06@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">ЭЛЕКТРОНИКА</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Ableton</a></h3>
                <p class="ticket-card__price"><span class="js-sum">88 000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Продам свое старое кресло, чтобы сидеть и читать книги зимними...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color07">
            <div class="ticket-card__img">
              <img src="img/item07.jpg" srcset="img/item07@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">Спорт и отдых</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Доска</a></h3>
                <p class="ticket-card__price"><span class="js-sum">55 000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Продаю дедушкины часы в&nbsp;прекрасном состоянии, ходят до...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color08">
            <div class="ticket-card__img">
              <img src="img/item08.jpg" srcset="img/item08@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">Куплю</span>
              <div class="ticket-card__categories">
                <a href="#">ЭЛЕКТРОНИКА</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Фотик Canon</a></h3>
                <p class="ticket-card__price"><span class="js-sum">32 000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Куплю вот такую итальянскую кофеварку, можно любой фирмы...</p>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </section>
  <section class="tickets-list">
    <h2 class="visually-hidden">Самые обсуждаемые предложения</h2>
    <div class="tickets-list__wrapper">
      <div class="tickets-list__header">
        <p class="tickets-list__title">Самые обсуждаемые</p>
      </div>
      <ul>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color09">
            <div class="ticket-card__img">
              <img src="img/item09.jpg" srcset="img/item09@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">Куплю</span>
              <div class="ticket-card__categories">
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Монстера</a></h3>
                <p class="ticket-card__price"><span class="js-sum">1000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Куплю монстеру зеленую в хорошем зеленом состоянии, буду поливать...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color10">
            <div class="ticket-card__img">
              <img src="img/item10.jpg" srcset="img/item10@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Мое старое кресло</a></h3>
                <p class="ticket-card__price"><span class="js-sum">4000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Продам свое старое кресло, чтобы сидеть и читать книги зимними...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color11">
            <div class="ticket-card__img">
              <img src="img/item11.jpg" srcset="img/item11@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">ПРОДАМ</span>
              <div class="ticket-card__categories">
                <a href="#">ЭЛЕКТРОНИКА</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Дедушкины часы</a></h3>
                <p class="ticket-card__price"><span class="js-sum">45 000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Продаю дедушкины часы в&nbsp;прекрасном состоянии, ходят до...</p>
              </div>
            </div>
          </div>
        </li>
        <li class="tickets-list__item">
          <div class="ticket-card ticket-card--color04">
            <div class="ticket-card__img">
              <img src="img/item04.jpg" srcset="img/item04@2x.jpg 2x" alt="Изображение товара">
            </div>
            <div class="ticket-card__info">
              <span class="ticket-card__label">Куплю</span>
              <div class="ticket-card__categories">
                <a href="#">Дом</a>
              </div>
              <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="#">Кофеварка</a></h3>
                <p class="ticket-card__price"><span class="js-sum">2000</span> ₽</p>
              </div>
              <div class="ticket-card__desc">
                <p>Куплю вот такую итальянскую кофеварку, можно любой фирмы...</p>
              </div>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </section>
<?php endif; ?>
