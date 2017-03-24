<?php
namespace frontend\controllers;


use Yii;
use common\models\User;
use common\models\Category;
use common\models\Income;
use common\models\Outcome;
use frontend\components\Transactor;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Wallet;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public $data = "fooo";
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
        return $this->render('index', [
            'currencies' => Wallet::CURRENCIES
        ]);
    }


    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['/authorisation']);
    }


    public function actionIncome()
    {
        $model = new Income();
        $limit = ArrayHelper::getValue(Yii::$app->request->get(), 'limit');
        if(!$limit) $limit = 10;

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $income = ArrayHelper::getValue(Yii::$app->request->post(), 'Income');
                $model->setAttribute('walletId', (integer)ArrayHelper::getValue($income, 'walletId'));
                $model->setAttribute('categoryId', (integer)ArrayHelper::getValue($income, 'categoryId'));

                Transactor::addIncome($model->walletId, $model->categoryId, $model->value);
                $model->save();
                $this->refresh();
            }
        }

        $transactions = Income::find()
            ->where(['userId' => Yii::$app->getUser()->id])
            ->with(['wallet','category'])
            ->limit($limit)
            ->orderBy('createdAt DESC')
            ->all();

        $categories = Category::find()
            ->where([
                'active' => Category::ACTIVE,
                'userId' => Yii::$app->getUser()->id,
                'type' => Category::INCOME_CATEGORY])
            ->orderBy('activity DESC')
            ->asArray()
            ->all();

        $wallets = Wallet::find()
            ->where([
                'active' => Wallet::ACTIVE,
                'userId' => Yii::$app->getUser()->id])
            ->asArray()
            ->all();

        return $this->render('income', [
            'transactions' => $transactions,
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'wallets' =>  ArrayHelper::map($wallets, 'id', 'name')
        ]);
    }

    public function actionOutcome()
    {
        $model = new Outcome();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $income = ArrayHelper::getValue(Yii::$app->request->post(), 'Outcome');
                $model->setAttribute('walletId', (integer)ArrayHelper::getValue($income, 'walletId'));
                $model->setAttribute('categoryId', (integer)ArrayHelper::getValue($income, 'categoryId'));

                Transactor::addOutcome($model->walletId, $model->categoryId, $model->value);
                $model->save();
                $this->refresh();
            }
        }

        $transactions = Outcome::find()
            ->where(['userId' => Yii::$app->getUser()->id])
            ->with(['wallet','category'])
            ->limit(10)
            ->orderBy('createdAt DESC')
            ->all();

        $categories = Category::find()
            ->where([
                'active' => Category::ACTIVE,
                'userId' => Yii::$app->getUser()->id,
                'type' => Category::OUTCOME_CATEGORY])
            ->orderBy('activity DESC')
            ->asArray()
            ->all();

        $wallets = Wallet::find()
            ->where([
                'active' => Wallet::ACTIVE,
                'userId' => Yii::$app->getUser()->id])
            ->asArray()
            ->all();

        return $this->render('outcome', [
            'transactions' => $transactions,
            'model' => $model,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'wallets' =>  ArrayHelper::map($wallets, 'id', 'name')
        ]);
    }

    public function actionCategory()
    {
        $model = new Category();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->save();
                $this->refresh();
            }
        }

        $outcome = Category::find()
            ->where([
                'userId' => Yii::$app->getUser()->id,
                'active' => Category::ACTIVE,
                'type' => Category::OUTCOME_CATEGORY ])
            ->all();

        $income = Category::find()
            ->where([
                'userId' => Yii::$app->getUser()->id,
                'active' => Category::ACTIVE,
                'type' => Category::INCOME_CATEGORY ])
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
                $model->save();
                $this->refresh();
            }
        }

        $walletList = Wallet::find()
            ->where([
                'active' => Wallet::ACTIVE,
                'userId' => Yii::$app->getUser()->id])
            ->all();

        return $this->render('wallet', [
            'walletList' => $walletList,
            'model' => $model,
            'currencies' => Wallet::CURRENCIES
        ]);
    }

    public function actionSettings ()
    {
        $user = User::findOne(Yii::$app->user->id);

        if(Yii::$app->request->isAjax){

            $file = UploadedFile::getInstanceByName('file');

            $extension = explode('/',$file->type);
            $extension = strtolower(end($extension));
            $key = md5(uniqid());
            $fileName = "{$key}.{$extension}";

            $uploadResult = Yii::$app->clientS3->uploadAvatar($fileName, $file->tempName);
            $publicUrl = Yii::$app->clientS3->getAvatarUrl($fileName);
            if($user->avatarUrl) {
                Yii::$app->clientS3->deleteAvatar($fileName);
            }
            $user->setAttribute('avatarUrl', $publicUrl);

            if($user->save()){
                return Json::encode(['url' => $publicUrl]);
            }
        }

        if(Yii::$app->request->isPost){
            if($user->load(Yii::$app->request->post())){
                $user->save();
                $this->refresh();
            };
        }

        return $this->render('settings', [
            'user' => $user,
        ]);
    }
}
