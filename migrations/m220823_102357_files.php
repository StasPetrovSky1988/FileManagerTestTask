<?php

use yii\db\Migration;

/**
 * Class m220823_102357_file
 */
class m220823_102357_files extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->db->schema->getTableSchema('files') !== null) $this->dropTable('files');

        $this->createTable('files', array(
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'status' => $this->integer()->defaultValue(10),
            'id_user' => $this->integer(),
            'id_parent' => $this->integer()->defaultValue(null),
            'size' => $this->integer(),
            'type' => $this->string(), //folder or file
            'extension' => $this->string(),
            'share_date' => $this->bigInteger(),
            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger(),
        ), "ENGINE=InnoDB  DEFAULT CHARSET=utf8 DEFAULT COLLATE utf8_general_ci AUTO_INCREMENT=1;)");

        $this->addForeignKey('fk-files-to-user', 'files', 'id_user', 'users', 'id', 'CASCADE');
        $this->addForeignKey('fk-files-to-folder', 'files', 'id_parent', 'files', 'id', 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (Yii::$app->db->schema->getTableSchema('files') !== null) $this->dropTable('files');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220823_102357_file cannot be reverted.\n";

        return false;
    }
    */
}
