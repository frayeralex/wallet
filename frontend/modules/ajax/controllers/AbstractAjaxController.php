<?php


namespace frontend\modules\ajax\controllers;


use phpDocumentor\Reflection\Types\Array_;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class AbstractAjaxController extends Controller
{
    /**
     * @param string $key
     * @return mixed|null
     */
    protected function getValue($key)
    {
        return $key ? ArrayHelper::getValue(Yii::$app->request->get(), $key) : null;
    }
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
     * @return bool
     */
    protected function checkOwner(ActiveRecord $model)
    {
        if($model->userId !== Yii::$app->user->id){
            Yii::$app->response->statusCode = 403;
            Yii::$app->response->send();
        }else{
            return true;
        }
    }

    /**
     * @param ActiveRecord $model
     * @return bool
     */
    protected function checkModel(ActiveRecord $model)
    {
        if(empty($model)) {
            Yii::$app->response->statusCode = 404;
            Yii::$app->response->send();
        }else{
            return true;
        }
    }

    /**
     * @param $arr array
     * @return string - JSON
     */
    protected function toJson($arr)
    {
        return $arr ? Json::encode($arr) : Json::encode([]);
    }

}