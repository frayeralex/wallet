<?php


namespace frontend\modules\ajax\controllers;

use common\models\Wallet;
use Yii;

class WalletController extends AbstractAjaxController
{

    /**
     * Update Wallet record
     */
    public function actionUpdate()
    {
        if(Yii::$app->request->isAjax){
            $wallet = Wallet::findOne($this->getPostValue('id'));
            $this->checkModel($wallet);
            $this->checkOwner($wallet);

            $wallet->setAttributes(Yii::$app->request->post());
            if(!$wallet->validate()) Yii::$app->response->send(400);
            $code = $wallet->save() ? 200 : 500;
            Yii::$app->response->send($code);
        }
    }


    /**
     * Change Wallet active prop (remove)
     */
    public function actionRemove()
    {
        if(Yii::$app->request->isAjax){
            $wallet = Wallet::findOne($this->getPostValue('id'));
            $this->checkModel($wallet);
            $this->checkOwner($wallet);
            $wallet->active = Wallet::DISACTIVE;
            $code = $wallet->save() ? 200 : 500;
            Yii::$app->response->send($code);
        }
    }

    public function actionReport()
    {
        if(Yii::$app->request->isAjax){
            $wallet = Wallet::findOne($this->getValue('id'));
            $from = $this->getValue('dateFrom');
            $to = $this->getValue('dateTo');
            $this->checkModel($wallet);
            $this->checkOwner($wallet);

            $incomes = $wallet->getIncomes()
                ->where(['between', 'createdAt', $from, $to])
                ->asArray()
                ->orderBy('createdAt DESC')
                ->all();
            $outcomes = $wallet->getOutcomes()
                ->where(['between', 'createdAt', $from, $to])
                ->orderBy('createdAt DESC')
                ->asArray()
                ->all();

            return $this->toJson([
                'incomes' => $incomes,
                'outcomes' => $outcomes,
                'wallet' => $wallet->toArray()
            ]);
        }
    }

    public function actionTransaction()
    {
        if(Yii::$app->request->isAjax){
            $walletFrom = Wallet::findOne($this->getPostValue('from'));
            $walletTo = Wallet::findOne($this->getPostValue('to'));
            $value = (int)$this->getPostValue('valFrom');
            $rate = (int)$this->getPostValue('rate');

            $transaction = Wallet::getDb()->beginTransaction();
            try{
                $walletFrom->value = $walletFrom->value - $value;
                $walletTo->value = $walletTo->value + ($value * $rate);
                if($walletTo->save() && $walletFrom->save()){
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                    Yii::$app->response->send(500);
                }
            } catch(\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch(\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
            Yii::$app->response->send(200);
        }
    }
}