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


    public function actionUpdateCategoryName()
    {
        if(Yii::$app->request->isAjax){
            $id = ArrayHelper::getValue(Yii::$app->request->post(), 'id');
            $name = ArrayHelper::getValue(Yii::$app->request->post(), 'name');

            $category = Category::findOne($id);
            $category->name = $name;
            $category->save();
            return Json::encode(['result' => 'ok']);
        }
    }

    public function actionRemoveCategory()
    {
        if(Yii::$app->request->isAjax){
            $id = ArrayHelper::getValue(Yii::$app->request->post(), 'id');

            $category = Category::findOne($id);
            $category->active = Category::DISACTIVE;
            $category->save();

            return Json::encode(['result' => 'ok']);
        }
    }
}