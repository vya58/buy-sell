<?php

use yii\db\Migration;

/**
 * Class m221210_182053_add_fk_comment_table
 */
class m221210_182053_add_fk_comment_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->addForeignKey(
        'fk_comment_owner_id',
        'comment',
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
        echo "m221210_182053_add_fk_comment_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m221210_182053_add_fk_comment_table cannot be reverted.\n";

        return false;
    }
    */
}
