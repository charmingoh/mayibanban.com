<?php

use yii\db\Migration;

/**
 * Handles the creation for table `local_auth`.
 */
class m160519_103209_create_local_auth extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%local_auth}}', [
            'id'                   => $this->primaryKey(),
            'user_id'              => $this->integer()->notNull(),
            'email'                => $this->string()->notNull()->unique(),
            'password_hash'        => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'created_at'           => $this->integer()->notNull(),
            'updated_at'           => $this->integer()->notNull(),
            'is_deleted'           => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%local_auth}}');
    }
}
