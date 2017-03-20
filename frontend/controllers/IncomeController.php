<?php


namespace frontend\controllers;


use yii\rest\ActiveController;
use common\models\Income;

class IncomeController extends ActiveController
{
    public $modelClass = 'common\models\Income';

    public function actions()
    {
        return parent::actions();
    }

    public function actionIndex()
    {
        $income = Income::find()->asArray()->limit(5)->all();

        return $income;
    }
}