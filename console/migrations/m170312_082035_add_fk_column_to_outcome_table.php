<?php

use yii\db\Migration;

/**
 * Handles adding fk to table `outcome`.
 * Has foreign keys to the tables:
 *
 * - `user`
 * - `category`
 * - `wallet`
 */
class m170312_082035_add_fk_column_to_outcome_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('outcome', 'userId', $this->integer()->notNull());
        $this->addColumn('outcome', 'categoryId', $this->integer()->notNull());
        $this->addColumn('outcome', 'walletId', $this->integer()->notNull());

        // creates index for column `userId`
        $this->createIndex(
            'idx-outcome-userId',
            'outcome',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-outcome-userId',
            'outcome',
            'userId',
            'user',
            'id',
            'CASCADE'
        );

        // creates index for column `categoryId`
        $this->createIndex(
            'idx-outcome-categoryId',
            'outcome',
            'categoryId'
        );

        // add foreign key for table `category`
        $this->addForeignKey(
            'fk-outcome-categoryId',
            'outcome',
            'categoryId',
            'category',
            'id',
            'CASCADE'
        );

        // creates index for column `walletId`
        $this->createIndex(
            'idx-outcome-walletId',
            'outcome',
            'walletId'
        );

        // add foreign key for table `wallet`
        $this->addForeignKey(
            'fk-outcome-walletId',
            'outcome',
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
            'fk-outcome-userId',
            'outcome'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-outcome-userId',
            'outcome'
        );

        // drops foreign key for table `category`
        $this->dropForeignKey(
            'fk-outcome-categoryId',
            'outcome'
        );

        // drops index for column `categoryId`
        $this->dropIndex(
            'idx-outcome-categoryId',
            'outcome'
        );

        // drops foreign key for table `wallet`
        $this->dropForeignKey(
            'fk-outcome-walletId',
            'outcome'
        );

        // drops index for column `walletId`
        $this->dropIndex(
            'idx-outcome-walletId',
            'outcome'
        );

        $this->dropColumn('outcome', 'userId');
        $this->dropColumn('outcome', 'categoryId');
        $this->dropColumn('outcome', 'walletId');
    }
}
