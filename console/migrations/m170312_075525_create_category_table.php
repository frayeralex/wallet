<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m170312_075525_create_category_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'type' => $this->string(10)->notNull(),
            'userId' => $this->integer()->notNull(),
            'createdAt' => $this->datetime()->notNull(),
            'updatedAt' => $this->datetime(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            'idx-category-userId',
            'category',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-category-userId',
            'category',
            'userId',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-category-userId',
            'category'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-category-userId',
            'category'
        );

        $this->dropTable('category');
    }
}
