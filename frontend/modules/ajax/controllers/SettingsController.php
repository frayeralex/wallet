<?php


namespace frontend\modules\ajax\controllers;

use common\models\User;
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

    public function actionRemoveAvatar()
    {
        if(Yii::$app->request->isAjax){
            $user = User::findOne(Yii::$app->user->id);
            Yii::$app->clientS3->deleteAvatar($user->avatarUrl);
            $user->avatarUrl = null;
            Yii::$app->response->statusCode = $user->save() ? 200 : 500;
        }
    }
}