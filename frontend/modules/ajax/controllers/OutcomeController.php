<?php


namespace frontend\modules\ajax\controllers;


use Yii;
use common\models\Outcome;
use frontend\helpers\Transactor;
use common\models\Wallet;



class OutcomeController extends AbstractAjaxController
{
    /**
     * Return JSON array of user Outcomes
     */
    public function actionIndex ()
    {
        $outcomes = [];

        $currency = (int)$this->getValue('currency', 0);
        if(Yii::$app->request->isAjax){
            $outcomes = Outcome::find()
                ->joinWith('wallet')
                ->andWhere([
                    'outcome.userId' => Yii::$app->getUser()->id,
                    'wallet.currency' => Wallet::CURRENCIES[$currency]])
                ->with('category')
                ->asArray()
                ->all();
        }
        return $this->toJson($outcomes);
    }

    /**
     * Update outcome by ajax request
     * @postParam id string - AJAX require param
     * @postParam title string - AJAX optional param
     * @postParam value string - AJAX optional param
     * @postParam createdAt string - AJAX optional param
     */
    public function actionUpdate()
    {
        if(Yii::$app->request->isAjax){
            $outcome = Outcome::findOne($this->getPostValue('id'));
            $this->checkModel($outcome);
            $this->checkOwner($outcome);

            $outcome->setAttributes(Yii::$app->request->post());
            if(!$outcome->validate()) return Yii::$app->response->statusCode = 400;

            Yii::$app->response->statusCode = Transactor::updateOutcome($outcome) ? 200 : 500;
            Yii::$app->response->send();
        }
    }

    /**
     * Delete outcome by ajax request
     * @postParam id string - AJAX require param
     */
    public function actionRemove()
    {
        if(Yii::$app->request->isAjax){
            $outcome = Outcome::findOne($this->getPostValue('id'));
            $this->checkModel($outcome);
            $this->checkOwner($outcome);

            Yii::$app->response->statusCode = Transactor::removeOutcome($outcome) ? 200 : 500;
            Yii::$app->response->send();
        }
    }
}