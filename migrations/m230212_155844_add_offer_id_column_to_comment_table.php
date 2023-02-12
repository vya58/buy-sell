<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%comment}}`.
 */
class m230212_155844_add_offer_id_column_to_comment_table extends Migration
{

  /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%comment}}', 'offer_id', $this->integer()->notNull()->after('owner_id'));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('{{%comment}}', 'offer_id');
    }
}
