<?php

namespace app\models\forms;

use app\models\Category;
use app\models\Offer;
use app\models\OfferCategory;
use app\models\exceptions\DataSaveException;
use app\models\exceptions\FileExistException;
use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

class OfferAddForm extends Model
{
  public string $offerImage  = '';
  public string $offerTitle = '';
  public string $offerText = '';
  public string $offerType = '';
  public array $categories;
  public int $offerPrice;

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['offerTitle', 'offerText', 'categories', 'offerPrice', 'offerType'], 'required', 'message' => 'Обязательное поле'],
      [['offerTitle'], 'string', 'min' => Offer::MIN_LENGTH_TICKET_NAME, 'max' => Offer::MAX_LENGTH_TICKET_NAME],
      [['offerText'], 'string', 'min' => Offer::MIN_LENGTH_TICKET_COMMENT, 'max' => Offer::MAX_LENGTH_TICKET_COMMENT],
      [['offerPrice'], 'integer', 'min' => Offer::MIN_TICKET_PRICE],
      [['categories'], 'each', 'rule' => ['exist', 'skipOnError' => true, 'targetClass' => Category::class, 'targetAttribute' => ['categories' => 'category_id']]],
      [['offerImage'], 'file', 'skipOnEmpty' => true, 'checkExtensionByMimeType' => true, 'extensions' => 'jpg, png', 'wrongExtension' => 'Только форматы jpg и png'],
    ];
  }

  /**
   * @inheritDoc
   *
   * @return array
   */
  public function attributeLabels(): array
  {
    return [
      'offerImage' => 'Загрузить аватар',
      'offerTitle' => 'Название',
      'offerText' => 'Описание',
      'categories' => 'Категории публикации',
      'offerPrice' => 'Цена',
      'offerType' => 'Тип публикации',
    ];
  }

  /**
   * Метод автозаполнения полей формы редактирования объявления данными из БД
   *
   * @param object $form - форма настройки профиля пользователя
   * @param Offer $offer - объект класса Offer
   */
  public function autocompleteForm($form, $offer): void
  {
    if ($offer->offer_image) {
      $form->offerImage = $offer->offer_image;
    }

    $form->offerTitle = $offer->offer_title;
    $form->offerText = $offer->offer_text;
    $form->categories = $offer->categories;
    $form->offerPrice = $offer->offer_price;
    $form->offerType = $offer->offer_type;
  }

  /**
   * Метод сохранения данных из формы добавления публикации в БД
   *
   * @param int|null $id - id публикации
   *
   * @throws DataSaveException
   * @throws FileExistException
   */
  public function addOffer($id = null): ?int
  {
    if ($id) {
      $offer = Offer::findOne($id);
    } else {
      $offer = new Offer;
    }

    $offerImage = UploadedFile::getInstance($this, 'offerImage');

    if (!$offerImage) {
      $offerImage = '';
    }

    $this->offerImage = $offerImage;

    if (!$this->uploadImage($offer, $offerImage) && $this->offerImage) {
      throw new FileExistException('Загрузить изображение не удалось');
    }

    $offer->owner_id = Yii::$app->user->id;
    $offer->offer_type = $this->offerType;
    $offer->offer_title = $this->offerTitle;
    $offer->offer_text = $this->offerText;
    $offer->offer_price = $this->offerPrice;

    $transaction = Yii::$app->db->beginTransaction();

    try {
      if (!$offer->save()) {
        throw new DataSaveException('Не удалось создать объявление');
      }

      if (!empty($this->categories)) {
        foreach ($this->categories as $category) {
          $newCategory = new OfferCategory();
          $newCategory->offer_id = $offer->offer_id;
          $newCategory->category_id = $category;
          $newCategory->save();
        }
      }
      $transaction->commit();
    } catch (DataSaveException $exception) {
      $transaction->rollback();
      throw new DataSaveException($exception->getMessage());
    }
    return $offer->offer_id;
  }

  /**
   * Метод загрузки изображения объявления в БД
   *
   * @param Offer $offer - объект класса Offer
   * @param UploadedFile $offerImage - объект класса UploadedFile
   *
   * @return bool
   * @throws DataSaveException
   */
  public function uploadImage($offer, $offerImage): bool
  {
    if ($this->validate() && $this->offerImage) {
      // Создаем уникальное имя файла в БД
      $addedImageName = md5(microtime(true)) . '.' . $offerImage->getExtension();
      $offer->offer_image = $addedImageName;

      if (!$offerImage->saveAs('@webroot/' . Offer::OFFER_IMAGE_UPLOAD_PATH . $addedImageName)) {
        throw new DataSaveException('Ошибка загрузки изображения');
      }
      return true;
    }
    return false;
  }
}
