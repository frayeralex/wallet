<?php


namespace frontend\modules\ajax\controllers;

use Yii;
use frontend\components\DeclarationSearcher;

class SettingsController extends AbstractAjaxController
{
    public function actionDeclarations()
    {
        if(Yii::$app->request->isAjax){
            $text = $this->getPostValue('text');

            return $this->toJson(DeclarationSearcher::findByWords($text));
        }
    }
}