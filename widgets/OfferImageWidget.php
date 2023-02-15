<?php

namespace app\widgets;

use yii\base\Widget;
use app\models\Offer;

/**
 * Выводит изображение товара или картинку-заглушку
 *
 */
class OfferImageWidget extends Widget
{
  public $offerImage;

  public function run()
  {
    if ($this->offerImage) {
      $this->offerImage = Offer::OFFER_IMAGE_UPLOAD_PATH . $this->offerImage;
    } else {
      $this->offerImage = Offer::OFFER_IMAGE_STAB_PATH;
    }

    return $this->render('offer-image', ['offerImage' => $this->offerImage]);
  }
}
