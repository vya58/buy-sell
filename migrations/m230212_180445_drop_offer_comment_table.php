<?php

use yii\db\Migration;

/**
 * Handles the dropping of table `{{%offer_comment}}`.
 */
class m230212_180445_drop_offer_comment_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->dropTable('{{%offer_comment}}');
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->createTable('{{%offer_comment}}', [
      'id' => $this->primaryKey(),
      'owner_id' => $this->integer()->notNull(),
      'offer_id' => $this->integer()->notNull(),
      'comment_id' => $this->integer()->notNull(),

      $this->addForeignKey(
        'fk_offer_offer_id',
        'offer_comment',
        'offer_id',
        'offer',
        'offer_id',
        'CASCADE'
      ),

      $this->addForeignKey(
        'fk_comment_comment_id',
        'offer_comment',
        'comment_id',
        'comment',
        'comment_id',
        'CASCADE'
      ),

      $this->addPrimaryKey(
        'fk_offer_id_comment_id',
        'offer_comment',
        [
          `offer_id`,
          `comment_id`,
        ]
      ),
    ]);
  }
}
