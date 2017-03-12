<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\Income;
use Yii;
use yii\base\InvalidParamException;
use yii\helpers\ArrayHelper;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use common\models\Wallet;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }


    public function actionIncome()
    {
        $model = new Income();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->setAttribute('createdAt', date(DATE_ATOM, time()));
                $model->setAttribute('userId', Yii::$app->getUser()->id);
                $income = ArrayHelper::getValue(Yii::$app->request->post(), 'Income');
                $model->setAttribute('value', (integer)ArrayHelper::getValue($income, 'value'));
                $model->setAttribute('walletId', (integer)ArrayHelper::getValue($income, 'walletId'));
                $model->setAttribute('categoryId', (integer)ArrayHelper::getValue($income, 'categoryId'));
                $model->save();
                $this->refresh();
            }
        }else{
            $transactions = Income::find()
                ->where(['userId' => Yii::$app->getUser()->id])
                ->all();

            $categories = Category::find()
                ->where([
                    'userId' => Yii::$app->getUser()->id,
                    'type' => Category::INCOME_CATEGORY])
                ->asArray()
                ->all();

            $wallets = Wallet::find()
                ->where(['userId' => Yii::$app->getUser()->id])
                ->asArray()
                ->all();

            return $this->render('income', [
                'transactions' => $transactions,
                'model' => $model,
                'categories' => ArrayHelper::map($categories, 'id', 'name'),
                'wallets' =>  ArrayHelper::map($wallets, 'id', 'name')
            ]);
        }


    }

    public function actionCategory()
    {
        $model = new Category();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->createdAt = date(DATE_ATOM, time());
                $model->userId = Yii::$app->getUser()->id;
                $model->save();
                $this->refresh();
            }
        }

        $outcome = Category::find()
            ->where([
                'userId' => Yii::$app->getUser()->id,
                'type' => $model::OUTCOME_CATEGORY ])
            ->all();

        $income = Category::find()
            ->where([
                'userId' => Yii::$app->getUser()->id,
                'type' => $model::INCOME_CATEGORY ])
            ->all();

        return $this->render('category', [
            'model' => $model,
            'outcome' => $outcome,
            'income' => $income,
            'categories' => Category::CATEGORIES
        ]);
    }

    public function actionWallet()
    {
        $model = new Wallet();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->createdAt = date(DATE_ATOM, time());
                $model->userId = Yii::$app->getUser()->id;
                $model->save();
                $this->refresh();
            }
        }

        $walletList = Wallet::find()
            ->where(['userId' => Yii::$app->getUser()->id])
            ->all();

        return $this->render('wallet', [
            'walletList' => $walletList,
            'model' => $model,
            'currencies' => Wallet::CURRENCIES
        ]);
    }

    /**
     * Delete wallets
     */
    public function actionDeleteWallet ()
    {
        if(Yii::$app->request->isAjax){
            $wallet = Wallet::findOne(Yii::$app->request->post('id'));
            if($wallet){
                $wallet->delete();
                return Yii::$app->response->setStatusCode(200);
            }
        }
        return Yii::$app->response->setStatusCode(400);
    }


    /**
     * Signs user up.
     *
     * @return mixed
     */
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

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
