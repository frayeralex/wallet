<?php


namespace frontend\controllers;

use common\models\Category;
use common\models\Income;
use common\models\Outcome;
use common\models\Rate;
use common\models\Wallet;
use frontend\components\DeclarationSearcher;
use frontend\helpers\Transactor;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Yii;
use yii\web\Controller;

class AjaxController extends Controller
{
    /**
     * Return array of user Outcomes
     */
    public function actionUserLastTransactions ()
    {
        if(Yii::$app->request->isAjax){
            $start = ArrayHelper::getValue(Yii::$app->request->post(), 'dateStart');
            $end = ArrayHelper::getValue(Yii::$app->request->post(), 'dateEnd');
            $currency = (int)ArrayHelper::getValue(Yii::$app->request->post(), 'currency');
            if(!$start || !$end) return Json::encode(['err' => 'no data']);

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

            return Json::encode([
                'incomes' => $incomes,
                'outcomes' => $outcomes,
            ]);
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

    public function actionGetRates()
    {
        if(Yii::$app->request->isAjax){
            $rates = Rate::find()
                ->asArray()
                ->all();

            return Json::encode($rates);
        }
    }

    public function actionGetDeclarations()
    {
        if(Yii::$app->request->isAjax){
            $text = ArrayHelper::getValue(Yii::$app->request->post(), 'text');

            $result = DeclarationSearcher::findByWords($text);

            return $result ? Json::encode($result) : Json::encode([]);
        }
    }

}