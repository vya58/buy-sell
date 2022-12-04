<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%auth}}`.
 *
 * Миграция по созданию таблицы 'auth' в БД для хранения инфорации об аутентификации пользователей через внешние сервисы
 * Поле 'user_id' в таблице 'auth' - id пользователя
 * Поле 'source' - название используемого провайдера аутентификации
 * Поле 'source_id' - уникальный идентификатор пользователя, который предоставляется внешним сервисом после успешной аутентификации.
 */
class m221203_133911_create_auth_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
      $this->createTable('auth', [
        'id' => $this->primaryKey(),
        'user_id' => $this->integer()->notNull(),
        'source' => $this->string()->notNull(),
        'source_id' => $this->string()->notNull(),
    ]);

    $this->addForeignKey('fk-auth-user_id-user-id', 'auth', 'user_id', 'user', 'user_id', 'CASCADE', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%auth}}');
    }
}
