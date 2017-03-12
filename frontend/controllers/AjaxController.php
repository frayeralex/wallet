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
    public function actionIndex ()
    {
        $incomes = Income::find()
            ->where(['userId' => Yii::$app->getUser()->id])
            ->asArray()
            ->all();

        $categories = Category::find()
            ->where(['id' => ArrayHelper::getColumn($incomes,'categoryId')])
            ->asArray()
            ->all();

        if(Yii::$app->request->isAjax){
            return Json::encode([
                "incomes" => $incomes,
                "category" => $categories,
            ]);
        }
    }
}