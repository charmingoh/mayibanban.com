<?php

use yii\db\Migration;

/**
 * Handles the creation for table `site_log`.
 */
class m160519_104236_create_site_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%site_log}}', [
            'id'         => $this->primaryKey()->comment('更新次数'),
            'detail'     => $this->string(2048)->notNull()->comment('更新细节'),
            'version'    => $this->string(32)->notNull()->comment('版本号'),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->integer()->notNull(),
            'is_deleted' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%site_log}}');
    }
}
