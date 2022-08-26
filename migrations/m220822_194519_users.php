<?php

use yii\db\Migration;

/**
 * Class m220822_194519_user
 */
class m220822_194519_users extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (Yii::$app->db->schema->getTableSchema('users') !== null) $this->dropTable('users');

        $this->createTable('users',array(
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'auth_key' => $this->string(32),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->bigInteger(),
            'updated_at' => $this->bigInteger(),
            'id_image' => $this->integer(),
            'id_group' => $this->integer()->notNull()->defaultValue(0),
            'email' => $this->string()->notNull()->unique(),
            'access_level' => $this->smallInteger()->notNull()->defaultValue(0), //Уровень доступа
            'verification_token' => $this->string()->defaultValue(null),
        ));

        $this->insert('users', [
            'username' => 'Станислав Петров',
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('qwerty'),
            'status' => 10,
            'created_at' => (new DateTime())->getTimestamp(),
            'updated_at' => (new DateTime())->getTimestamp(),
            'email' => 'stas.petrov@test.com',
            'access_level' => 7,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (Yii::$app->db->schema->getTableSchema('users') !== null) $this->dropTable('users');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220822_194519_user cannot be reverted.\n";

        return false;
    }
    */
}
