<?php


namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use frontend\models\LoginForm;
use frontend\models\SignupForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use yii\web\BadRequestHttpException;
use yii\base\InvalidParamException;
use frontend\components\AuthHandler;

class AuthController extends Controller
{
    public $layout = 'auth';

    public function actions()
    {
        return [
            'auth' => [
                'class' => 'yii\authclient\AuthAction',
                'successCallback' => [$this, 'onAuthSuccess'],
            ],
        ];
    }

    public function onAuthSuccess($client)
    {
        (new AuthHandler($client))->handle();
    }

    public function beforeAction($event)
    {
        if(!Yii::$app->user->isGuest){
            return $this->goHome();
        }
        return parent::beforeAction($event);
    }

    public function actionIndex()
    {

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        if(Yii::$app->request->isAjax){
            Yii::$app->user->logout();

            $this->goHome();
        }
    }

}