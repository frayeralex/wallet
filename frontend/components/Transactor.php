<?php


namespace frontend\components;


use common\models\Category;
use common\models\Income;
use common\models\Outcome;
use common\models\Wallet;
use yii\base\Component;

class Transactor extends Component
{
    static function addIncome(Income $model)
    {
        $transaction = Income::getDb()->beginTransaction();
        try {
            $model->category->updateCounters(['activity' => 1]);
            $wallet = $model->wallet;
            $wallet->value += $model->value;
            $wallet->save();
            $model->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    static function addOutcome(Outcome $model)
    {
        $transaction = Outcome::getDb()->beginTransaction();
        try {
            $model->category->updateCounters(['activity' => 1]);
            $wallet = $model->wallet;
            $wallet->value -= $model->value;
            $wallet->save();
            $model->save();
            $transaction->commit();
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    static function updateOutcome($walletId, $oldValue, $newValue)
    {
        if(!$walletId || !$oldValue || !$newValue) return;

        $wallet = Wallet::findOne($walletId);

        $diff = $oldValue - $newValue;
        $wallet->value = $wallet->value + $diff;
        $wallet->save();
    }

    static function updateIncome($walletId, $oldValue, $newValue)
    {
        if(!$walletId || !$oldValue || !$newValue) return;

        $wallet = Wallet::findOne($walletId);

        $diff = $oldValue - $newValue;
        $wallet->value = $wallet->value - $diff;
        $wallet->save();
    }

    static function removeIncome($walletId, $categoryId, $value)
    {
        if(!$walletId || !$categoryId || !$value) return;

        Category::findOne($categoryId)->updateCounters(['activity' => -1]);

        $wallet = Wallet::findOne($walletId);

        $wallet->value = $wallet->value - $value;
        $wallet->save();
    }

    static function removeOutcome($walletId, $categoryId, $value)
    {
        if(!$walletId || !$categoryId || !$value) return;

        Category::findOne($categoryId)->updateCounters(['activity' => -1]);

        $wallet = Wallet::findOne($walletId);

        $wallet->value = $wallet->value + $value;
        $wallet->save();
    }
}