<?php


namespace frontend\modules\ajax\controllers;

use Yii;
use common\models\Wallet;
use common\models\Income;
use common\models\Outcome;


class AnalyticController extends AbstractAjaxController
{
    /**
     * Return array of user Outcomes
     */
    public function actionIndex ()
    {
        if(Yii::$app->request->isAjax){
            $start = $this->getValue('dateStart');
            $end = $this->getValue('dateEnd');
            $currency = (int)$this->getValue('currency');
            if(!$start || !$end) return $this->toJson(['err' => 'no data']);

            $incomes = Income::find()
                ->where(['between', 'income.createdAt', $start, $end])
                ->joinWith('wallet')
                ->andWhere([
                    'income.userId' => Yii::$app->getUser()->id,
                    'wallet.currency' => Wallet::CURRENCIES[$currency]])
                ->asArray()
                ->all();

            $outcomes = Outcome::find()
                ->where(['between', 'outcome.createdAt', $start, $end])
                ->joinWith('wallet')
                ->andWhere([
                    'outcome.userId' => Yii::$app->getUser()->id,
                    'wallet.currency' => Wallet::CURRENCIES[$currency]])
                ->asArray()
                ->all();

            return $this->toJson([
                'incomes' => $incomes,
                'outcomes' => $outcomes,
            ]);
        }
    }

}