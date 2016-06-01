<?php

use yii\db\Migration;

/**
 * Handles the creation for table `answer`.
 */
class m160519_104641_create_answer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        
        $this->createTable('{{%answer}}', [
            'id'             => $this->primaryKey(),
            'question_id'    => $this->integer()->notNull()->comment('问题ID'),
            'url'            => $this->string(1024)->notNull()->comment('链接'),
            'title'          => $this->string(64)->notNull()->comment('链接标题'),
            'digest'         => $this->string(8192)->defaultValue('')->comment('推荐语'),
            'content'        => $this->string(8192)->notNull()->defaultValue('')->comment('链接内容'),
            'view_count'     => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('浏览数量'),
            'like_count'     => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('点赞数量'),
            'favorite_count' => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('收藏数量'),
            'comment_count'  => $this->integer()->unsigned()->notNull()->defaultValue(0)->comment('评论数量'),
            'status'         => $this->string(16)->notNull()->defaultValue('active')->comment('状态'),
            'updated_at'     => $this->integer()->notNull(),
            'created_by'     => $this->integer()->notNull(),
            'created_at'     => $this->integer()->notNull(),
            'is_deleted'     => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%answer}}');
    }
}
