<?php

use yii\db\Migration;

/**
 * Handles the creation of table `rate`.
 */
class m170322_121259_create_rate_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('rate', [
            'id' => $this->primaryKey(),
            'cc' => $this->string()->notNull(),
            'exchangedate' => $this->date(),
            'value' => $this->double()->notNull(),
            'label' => $this->string(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('rate');
    }
}
