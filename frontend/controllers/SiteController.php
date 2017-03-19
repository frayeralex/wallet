<?php
namespace frontend\controllers;

use common\models\Category;
use common\models\Income;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

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
                'only' => ['logout'],
                'rules' => [
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

    public function beforeAction($event)
    {
        if(Yii::$app->user->isGuest || $event->id === 'error'){
            return $this->redirect(['/authorisation']);
        }
        return parent::beforeAction($event);
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


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/authorisation']);
    }


    public function actionIncome()
    {
        $model = new Income();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->setAttribute('createdAt', date(DATE_ATOM, time()));
                $model->setAttribute('userId', Yii::$app->getUser()->id);
                $income = ArrayHelper::getValue(Yii::$app->request->post(), 'Income');
                $model->setAttribute('walletId', (integer)ArrayHelper::getValue($income, 'walletId'));
                $model->setAttribute('categoryId', (integer)ArrayHelper::getValue($income, 'categoryId'));
                $model->save();
                $this->refresh();
            }
        }else{
            $transactions = Income::find()
                ->where(['userId' => Yii::$app->getUser()->id])
                ->limit(10)
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
}
