<?php


namespace frontend\modules\ajax\controllers;

use Yii;
use common\models\Rate;

class RateController extends AbstractAjaxController
{
    public function actionIndex()
    {
        if(Yii::$app->request->isAjax){
            $rates = Rate::find()
                ->asArray()
                ->all();

            return $this->toJson($rates);
        }
    }
}