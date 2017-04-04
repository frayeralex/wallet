<?php


namespace frontend\modules\ajax\controllers;


use Yii;
use common\models\Income;
use frontend\helpers\Transactor;
use common\models\Wallet;

class IncomeController extends AbstractAjaxController
{
    /**
     * Return JSON array of user Incomes
     */
    public function actionIndex ()
    {
        $incomes = [];
        $currency = (int)$this->getValue('currency', 0);
        if(Yii::$app->request->isAjax){
            $incomes = Income::find()
                ->joinWith('wallet')
                ->andWhere([
                    'income.userId' => Yii::$app->getUser()->id,
                    'wallet.currency' => Wallet::CURRENCIES[$currency]])
                ->with('category')
                ->asArray()
                ->all();
        }
        return $this->toJson($incomes);
    }

    /**
     * Update income by ajax request
     * @postParam id string - AJAX require param
     * @postParam title string - AJAX optional param
     * @postParam value string - AJAX optional param
     * @postParam createdAt string - AJAX optional param
     */
    public function actionUpdate()
    {
        if(Yii::$app->request->isAjax){
            $income = Income::findOne($this->getPostValue('id'));
            $this->checkModel($income);
            $this->checkOwner($income);

            $income->setAttributes(Yii::$app->request->post());
            if(!$income->validate()) return Yii::$app->response->statusCode = 400 && Yii::$app->response->send();

            Yii::$app->response->statusCode = Transactor::updateIncome($income) ? 200 : 500;
            return Yii::$app->response->send();
        }
    }

    /**
     * Delete income by ajax request
     * @postParam id string - AJAX require param
     */
    public function actionRemove()
    {
        if(Yii::$app->request->isAjax){
            $income = Income::findOne($this->getPostValue('id'));
            $this->checkModel($income);
            $this->checkOwner($income);

            Yii::$app->response->statusCode = Transactor::removeIncome($income) ? 200 : 500;
            return Yii::$app->response->send();
        }
    }
}