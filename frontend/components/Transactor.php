<?php


namespace frontend\components;


use common\models\Category;
use common\models\Wallet;
use yii\base\Component;

class Transactor extends Component
{
    static function addIncome($walletId, $categoryId, $value)
    {
        if(!$walletId || !$categoryId || !$value) return;

        Category::findOne($categoryId)->updateCounters(['activity' => 1]);

        $wallet = Wallet::findOne($walletId);
        $wallet->value = $wallet->value + $value;
        $wallet->save();
    }

    static function addOutcome($walletId, $categoryId, $value)
    {
        if(!$walletId || !$categoryId || !$value) return;

        Category::findOne($categoryId)->updateCounters(['activity' => 1]);

        $wallet = Wallet::findOne($walletId);
        $wallet->value = $wallet->value - $value;
        $wallet->save();
    }
}