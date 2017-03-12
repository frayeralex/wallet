<?php

use yii\db\Migration;

/**
 * Handles the creation of table `wallet`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m170312_075937_create_wallet_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('wallet', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'value' => $this->integer()->defaultValue(0),
            'userId' => $this->integer()->notNull(),
            'createdAt' => $this->datetime()->notNull(),
            'updatedAt' => $this->datetime(),
        ]);

        // creates index for column `userId`
        $this->createIndex(
            'idx-wallet-userId',
            'wallet',
            'userId'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-wallet-userId',
            'wallet',
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
            'fk-wallet-userId',
            'wallet'
        );

        // drops index for column `userId`
        $this->dropIndex(
            'idx-wallet-userId',
            'wallet'
        );

        $this->dropTable('wallet');
    }
}
