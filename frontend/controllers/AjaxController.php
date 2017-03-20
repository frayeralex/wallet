<?php


namespace frontend\controllers;

use common\models\Category;
use common\models\Income;
use common\models\Outcome;
use common\models\Wallet;
use frontend\components\Transactor;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Yii;
use yii\web\Controller;

class AjaxController extends Controller
{
    /**
     * Return array of user Incomes
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

    /**
     * Return array of user Outcomes
     */
    public function actionUserOutcomes ()
    {
        $outcomes = Outcome::find()
            ->where(['userId' => Yii::$app->getUser()->id])
            ->with('category')
            ->asArray()
            ->all();

        if(Yii::$app->request->isAjax){
            return Json::encode($outcomes);
        }
    }

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

    public function actionUpdateOutcome()
    {
        if(Yii::$app->request->isAjax){
            $id = ArrayHelper::getValue(Yii::$app->request->post(), 'id');
            $title = ArrayHelper::getValue(Yii::$app->request->post(), 'title');
            $value = (float)ArrayHelper::getValue(Yii::$app->request->post(), 'value');
            $date = ArrayHelper::getValue(Yii::$app->request->post(), 'date');
            if(!$id || !$title || !$value || !$date) {
                return Yii::$app->response->statusCode = 400;
            }
            $outcome = Outcome::findOne($id);
            $outcome->setAttribute('title',$title);
            Transactor::updateOutcome($outcome->walletId, $outcome->value, $value);

            $outcome->setAttribute('value',$value);
            $outcome->setAttribute('createdAt', \Yii::$app->formatter->asDate($date, 'Y-MM-dd HH:i:s'));

            $outcome->save();

            return Json::encode(['result' => 'ok']);
        }
    }

    public function actionRemoveOutcome()
    {
        if(Yii::$app->request->isAjax){
            $id = ArrayHelper::getValue(Yii::$app->request->post(), 'id');
            $outcome = Outcome::findOne($id);
            Transactor::removeOutcome($outcome->walletId, $outcome->categoryId, $outcome->value);
            $outcome->delete();
            return Json::encode(['result' => 'ok']);
        }
    }

    public function actionUpdateIncome()
    {
        if(Yii::$app->request->isAjax){
            $id = ArrayHelper::getValue(Yii::$app->request->post(), 'id');
            $title = ArrayHelper::getValue(Yii::$app->request->post(), 'title');
            $value = (float)ArrayHelper::getValue(Yii::$app->request->post(), 'value');
            $date = ArrayHelper::getValue(Yii::$app->request->post(), 'date');
            if(!$id || !$title || !$value || !$date) {
                return Yii::$app->response->statusCode = 400;
            }
            $income = Income::findOne($id);
            $income->setAttribute('title',$title);
            Transactor::updateIncome($income->walletId, $income->value, $value);

            $income->setAttribute('value',$value);
            $income->setAttribute('createdAt', \Yii::$app->formatter->asDate($date, 'Y-MM-dd HH:i:s'));

            $income->save();

            return Json::encode(['result' => 'ok']);
        }
    }

    public function actionRemoveIncome()
    {
        if(Yii::$app->request->isAjax){
            $id = ArrayHelper::getValue(Yii::$app->request->post(), 'id');
            $income = Income::findOne($id);
            Transactor::removeIncome($income->walletId, $income->categoryId, $income->value);
            $income->delete();
            return Json::encode(['result' => 'ok']);
        }
    }

}