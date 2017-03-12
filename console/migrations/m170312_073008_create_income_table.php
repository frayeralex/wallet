<?php

use yii\db\Migration;

/**
 * Handles the creation of table `income`.
 */
class m170312_073008_create_income_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('income', [
            'id' => $this->primaryKey(),
            'title' => $this->string(),
            'createdAt' => $this->datetime()->notNull(),
            'updatedAt' => $this->datetime(),
            'value' => $this->integer()->notNull(),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('income');
    }
}
