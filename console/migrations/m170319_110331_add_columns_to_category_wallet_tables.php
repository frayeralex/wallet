<?php

use yii\db\Migration;

class m170319_110331_add_columns_to_category_wallet_tables extends Migration
{
    public function up()
    {
        $this->addColumn('wallet', 'active', $this->integer()->notNull());
        $this->addColumn('category', 'active', $this->integer()->notNull());
        $this->addColumn('category', 'activity', $this->integer()->notNull());
    }

    public function down()
    {
        $this->dropColumn('wallet', 'active');
        $this->dropColumn('category', 'active');
        $this->dropColumn('category', 'activity');
    }

}
