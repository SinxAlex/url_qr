<?php

use yii\db\Migration;

class m250528_101404_url extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%url}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'url_to' => $this->string()->notNull(),
            'url_short' => $this->string()->notNull(),
            'views'=>$this->integer()->notNull()
        ]);


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%url}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250528_101404_url cannot be reverted.\n";

        return false;
    }
    */
}
