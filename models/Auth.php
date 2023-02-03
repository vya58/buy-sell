<?php

namespace app\models;

use Yii;
use \yii\db\ActiveRecord;


/**
 * This is the model class for table "auth".
 *
 * @property int $id
 * @property int $user_id
 * @property string $source
 * @property string $source_id
 *
 * @property User $user
 */
class Auth extends ActiveRecord
{
  private const MAX_LENGTH_SOURSE = 255;

  /**
   * {@inheritdoc}
   *
   * @return string
   */
  public static function tableName(): string
  {
    return 'auth';
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   */
  public function rules(): array
  {
    return [
      [['user_id', 'source', 'source_id'], 'required'],
      [['user_id'], 'integer'],
      [['source', 'source_id'], 'string', 'max' => self::MAX_LENGTH_SOURSE],
      [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'user_id']],
    ];
  }

  /**
   * {@inheritdoc}
   *
   * @return array
   */
  public function attributeLabels(): array
  {
    return [
      'id' => 'ID',
      'user_id' => 'User ID',
      'source' => 'Source',
      'source_id' => 'Source ID',
    ];
  }

  /**
   * Gets query for [[User]].
   *
   * @return \yii\db\ActiveQuery|yii\db\ActiveQuery
   */
  public function getUser()
  {
    return $this->hasOne(User::class, ['user_id' => 'user_id']);
  }
}
