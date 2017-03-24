<?php

use yii\db\Migration;

/**
 * Handles adding fk to table `income`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 * - `wallet`
 */
class m170312_081623_add_fk_column_to_income_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('income', 'userId', $this->integer()->notNull());
        $this->addColumn('income', 'categoryId', $this->integer()->notNull());
        $this->addColumn('income', 'walletId', $this->integer()->notNull());

        // creates index for column `userId`
        $this->createIndex(
            'idx-income-userId',
            'income',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-income-userId',
            'income',
            'userId',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `categoryId`
        $this->createIndex(
            'idx-income-categoryId',
            'income',
            'categoryId'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-income-categoryId',
            'income',
            'categoryId',
            'category',
            'id',
            'CASCADE'
        );

        // creates index for column `walletId`
        $this->createIndex(
            'idx-income-walletId',
            'income',
            'walletId'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-income-walletId',
            'income',
            'walletId',
            'wallet',
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
            'fk-income-userId',
            'income'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-income-userId',
            'income'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-income-categoryId',
            'income'
        );

        // drops index for column `categoryId`
        $this->dropIndex(
            'idx-income-categoryId',
            'income'
        );

        // drops foreign key for table `wallet`
        $this->dropForeignKey(
            'fk-income-walletId',
            'income'
        );

        // drops index for column `walletId`
        $this->dropIndex(
            'idx-income-walletId',
            'income'
        );

        $this->dropColumn('income', 'userId');
        $this->dropColumn('income', 'categoryId');
        $this->dropColumn('income', 'walletId');
    }
}
