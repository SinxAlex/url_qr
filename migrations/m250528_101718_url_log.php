<?php

use yii\db\Migration;

class m250528_101718_url_log extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%url_log}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'id_url' => $this->integer()->notNull(),
            'url_from' => $this->string()->notNull(),
            'ip'=>$this->string(15)->notNull()
        ]);


        $this->createIndex(
            'idx-url_id',
            '{{%url_log}}',
            'id_url'
        );

        // Добавляем внешний ключ для связи product.category_id -> category.id
        $this->addForeignKey(
            'fk-url-key-id',
            '{{%url_log}}',
            'id_url',
            '{{%url}}',
            'id',
            'CASCADE', // Удаление категории приведет к удалению связанных продуктов
            'CASCADE'  // Обновление id категории обновит category_id в продуктах
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%url_log}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m250528_101718_url_log cannot be reverted.\n";

        return false;
    }
    */
}
