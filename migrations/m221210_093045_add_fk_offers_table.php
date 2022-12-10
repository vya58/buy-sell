<?php

use yii\db\Migration;

/**
 * Class m221210_093045_add_fk_offers_table
 */
class m221210_093045_add_fk_offers_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addForeignKey(
      'fk_offers_owner_id',
      'offer',
      'owner_id',
      'user',
      'user_id',
      'CASCADE'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    echo "m221210_093045_add_fk_offers_table cannot be reverted.\n";

    return false;
  }

  /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221210_093045_add_fk_offers_table cannot be reverted.\n";

        return false;
    }
    */
}
