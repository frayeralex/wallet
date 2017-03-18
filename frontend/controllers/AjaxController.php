<?php


namespace frontend\controllers;

use common\models\Category;
use common\models\Income;
use common\models\Wallet;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Yii;
use yii\web\Controller;

class AjaxController extends Controller
{
    /**
     * Incomes pie chart data
     */
    public function actionUserIncomes ()
    {
        $incomes = Income::find()
            ->where(['userId' => Yii::$app->getUser()->id])
            ->with('category')
            ->asArray()
            ->all();

        if(Yii::$app->request->isAjax){
            return Json::encode($incomes);
        }
    }
}