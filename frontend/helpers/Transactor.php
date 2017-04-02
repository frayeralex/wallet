<?php


namespace frontend\helpers;


use common\models\Income;
use common\models\Outcome;
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
            return true;
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
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    static function updateOutcome(Outcome $model)
    {
        $transaction = Outcome::getDb()->beginTransaction();
        try{
            $wallet = $model->wallet;
            $wallet->value = $wallet->value + ($model->value - $model->getOldAttribute('value'));
            $wallet->save();
            $model->save();
            $transaction->commit();
            return true;
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    static function removeOutcome(Outcome $model)
    {
        $transaction = Outcome::getDb()->beginTransaction();
        try {
            $model->category->updateCounters(['activity' => -1]);
            $wallet = $model->wallet;
            $wallet->value = $wallet->value + $model->value;
            $wallet->save();
            $model->delete();
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    static function updateIncome(Income $model)
    {
        $transaction = Income::getDb()->beginTransaction();
        try{
            $wallet = $model->wallet;
            $wallet->value = $wallet->value - ($model->getOldAttribute('value') - $model->value);
            $wallet->save();
            $model->save();
            $transaction->commit();
            return true;
        }catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }

    static function removeIncome(Income $model)
    {
        $transaction = Outcome::getDb()->beginTransaction();
        try {
            $model->category->updateCounters(['activity' => -1]);
            $wallet = $model->wallet;
            $wallet->value = $wallet->value - $model->value;
            $wallet->save();
            $model->delete();
            $transaction->commit();
            return true;
        } catch(\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch(\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}