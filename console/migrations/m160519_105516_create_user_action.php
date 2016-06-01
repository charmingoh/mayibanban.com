<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user_action`.
 */
class m160519_105516_create_user_action extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%user_action}}', [
            'id'          => $this->primaryKey(),
            'action'      => $this->string()->notNull()->comment('行为'),
            'target_type' => $this->string()->notNull()->comment('目标'),
            'target_id'   => $this->integer()->notNull()->comment('目标ID'),
            'created_by'  => $this->integer()->notNull(),
            'created_at'  => $this->integer()->notNull(),
            'is_deleted'  => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_action}}');
    }
}
