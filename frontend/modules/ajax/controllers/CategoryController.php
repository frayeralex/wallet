<?php


namespace frontend\modules\ajax\controllers;

use common\models\Category;
use Yii;

class CategoryController extends AbstractAjaxController
{
    public function actionUpdateCategoryName()
    {
        if(Yii::$app->request->isAjax){
            $category = Category::findOne($this->getPostValue('id'));
            $this->checkModel($category);
            $this->checkOwner($category);

            $category->setAttributes(Yii::$app->request->post());
            if(!$category->validate()) Yii::$app->response->send(400);
            $code = $category->save() ? 200 : 500;
            Yii::$app->response->send($code);
        }
    }

    public function actionRemoveCategory()
    {
        if(Yii::$app->request->isAjax){
            $category = Category::findOne($this->getPostValue('id'));
            $this->checkModel($category);
            $this->checkOwner($category);

            $category->active = Category::DISACTIVE;
            $code = $category->save() ? 200 : 500;
            Yii::$app->response->send($code);
        }
    }

}