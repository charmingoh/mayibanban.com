<?php

use yii\db\Migration;

/**
 * Handles the creation for table `question`.
 */
class m160519_105111_create_question extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%question}}', [
            'id'           => $this->primaryKey(),
            'title'        => $this->string(64)->notNull()->comment('问题'),
            'detail'       => $this->string(16384)->notNull()->defaultValue('')->comment('详细描述'),
            'view_count'   => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('浏览数量'),
            'answer_count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('回答数量'),
            'follow_count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('关注数量'),
            'status'       => $this->string(16)->notNull()->defaultValue('active')->comment('状态'),
            'updated_by'   => $this->integer()->notNull(),
            'updated_at'   => $this->integer()->notNull(),
            'created_by'   => $this->integer()->notNull(),
            'created_at'   => $this->integer()->notNull(),
            'is_deleted'   => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%question}}');
    }
}
