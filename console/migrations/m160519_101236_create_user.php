<?php

use yii\db\Migration;

/**
 * Handles the creation for table `user`.
 */
class m160519_101236_create_user extends Migration
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

        $this->createTable('{{%user}}', [
            'id'          => $this->primaryKey(),
            'alias'       => $this->string()->notNull()->comment('别名'),
            'name'        => $this->string()->notNull()->comment('用户姓名'),
            'avatar'      => $this->string(512)->defaultValue(null)->comment('用户头像'),
            'gender'      => $this->string(8)->notNull()->defaultValue('unknown')->comment('性别'),
            'email'       => $this->string()->defaultValue(null)->comment('邮箱'),
            'description' => $this->string(16384)->defaultValue('')->comment('描述'),
            'view_count'  => $this->integer()->unsigned()->defaultValue(0)->comment('浏览次数'),
            'auth_key'    => $this->string(32)->notNull(),
            'status'      => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at'  => $this->integer()->notNull(),
            'updated_at'  => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
