<?php

use yii\db\Migration;

/**
 * Handles adding info to table `user`.
 */
class m170312_080334_add_info_column_to_user_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('user', 'firstName', $this->string(50));
        $this->addColumn('user', 'lastName', $this->string(50));
        $this->addColumn('user', 'phone', $this->integer());
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('user', 'firstName');
        $this->dropColumn('user', 'lastName');
        $this->dropColumn('user', 'phone');
    }
}
