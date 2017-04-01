<?php


namespace frontend\modules\ajax\controllers;


use Yii;
use common\models\Income;
use frontend\helpers\Transactor;
use yii\web\Response;

class IncomeController extends AbstractAjaxController
{
    /**
     * Return JSON array of user Incomes
     */
    public function actionIndex ()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $incomes = [];

        if(Yii::$app->request->isAjax && !Yii::$app->user->isGuest){
            $incomes = Income::find()
                ->where(['userId' => Yii::$app->getUser()->id])
                ->with('category')
                ->asArray()
                ->all();
        }
        return $incomes;
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