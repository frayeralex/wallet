<?php

use yii\db\Migration;

/**
 * Handles the creation of table `utcome`.
 */
class m170312_073325_create_utcome_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('outcome', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50),
            'createdAt' => $this->datetime()->notNull(),
            'updatedAt' => $this->datetime(),
            'value' => $this->double()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('outcome');
    }
}
