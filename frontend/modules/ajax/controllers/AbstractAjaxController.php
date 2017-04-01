<?php


namespace frontend\modules\ajax\controllers;


use Yii;
use yii\web\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class AbstractAjaxController extends Controller
{
    /**
     * @param string $key
     * @return mixed|null
     */
    protected function getPostValue($key)
    {
        return $key ? ArrayHelper::getValue(Yii::$app->request->post(), $key) : null;
    }

    /**
     * @param ActiveRecord $model
     * @return int
     */
    protected function checkOwner(ActiveRecord $model)
    {
        if($model->userId !== Yii::$app->user->id){
            Yii::$app->response->statusCode = 403;
            return Yii::$app->response->send();
        }else{
            return true;
        }
    }

    protected function checkModel(ActiveRecord $model)
    {
        if(empty($model)) {
            Yii::$app->response->statusCode = 404;
            return Yii::$app->response->send();
        }else{
            return true;
        }

    }

}