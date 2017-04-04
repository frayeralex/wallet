<?php
namespace frontend\controllers;



use Yii;
use frontend\models\ChangePasswordForm;
use common\models\User;
use common\models\Category;
use common\models\Income;
use common\models\Outcome;
use frontend\helpers\Transactor;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;
use common\models\Wallet;
use yii\web\UploadedFile;

/**
 * Site controller
 */
class SiteController extends Controller
{
    public function beforeAction($event)
    {
        if(Yii::$app->user->isGuest || $event->id === 'error'){
            return $this->redirect(['/auth']);
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
        if(!Yii::$app->user->isGuest){
            $user = User::findOne(Yii::$app->getUser()->id);
            $wallets = $user->getWallets()
                ->where(['active' => Wallet::ACTIVE])
                ->asArray()
                ->all();
            $currencies = ArrayHelper::getColumn($wallets, 'currency');

            return $this->render('index', [
                'user' => $user,
                'currencies' => array_unique($currencies)
            ]);
        }
    }

    public function actionIncome()
    {
        $model = new Income();

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())){
                Transactor::addIncome($model);
                $this->refresh();
            }
        }

        $user = User::findOne(Yii::$app->getUser()->id);
        $categories = $user->getCategories()
            ->where([
                'active' => Category::ACTIVE,
                'type' => Category::INCOME_CATEGORY])
            ->orderBy('activity DESC')
            ->asArray()
            ->all();

        $transactions = $user->getIncomes()
            ->with(['wallet','category'])
            ->orderBy('createdAt DESC');

        $wallets = $user->getWallets()
            ->where(['active' => Wallet::ACTIVE])
            ->asArray()
            ->all();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $transactions->count()
        ]);

        $transactions = $transactions
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();


        return $this->render('income', [
            'user' => $user,
            'model' => $model,
            'transactions' => $transactions,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'wallets' =>  ArrayHelper::map($wallets, 'id', 'name'),
            'pagination' => $pagination
        ]);
    }

    public function actionOutcome()
    {
        $model = new Outcome();
        $limit = ArrayHelper::getValue(Yii::$app->request->get(), 'limit', 10);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post())){
                Transactor::addOutcome($model);
                $this->refresh();
            }
        }

        $user = User::findOne(Yii::$app->getUser()->id);
        $categories = $user->getCategories()
            ->where([
                'active' => Category::ACTIVE,
                'type' => Category::OUTCOME_CATEGORY])
            ->orderBy('activity DESC')
            ->asArray()
            ->all();
        $transactions = $user->getOutcomes()
            ->with(['wallet','category'])
            ->orderBy('createdAt DESC');

        $wallets = $user->getWallets()
            ->where(['active' => Wallet::ACTIVE])
            ->asArray()
            ->all();

        $pagination = new Pagination([
            'defaultPageSize' => 5,
            'totalCount' => $transactions->count()
        ]);

        $transactions = $transactions
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('outcome', [
            'user' => $user,
            'model' => $model,
            'transactions' => $transactions,
            'categories' => ArrayHelper::map($categories, 'id', 'name'),
            'wallets' =>  ArrayHelper::map($wallets, 'id', 'name'),
            'pagination' => $pagination
        ]);
    }

    public function actionCategory()
    {
        $model = new Category();
        $user = User::findOne(Yii::$app->getUser()->id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->save();
                $this->refresh();
            }
        }

        $income = $user->getCategories()
            ->where(['active' => Category::ACTIVE, 'type' => Category::INCOME_CATEGORY])
            ->orderBy('activity DESC')
            ->all();

        $outcome = $user->getCategories()
            ->where(['active' => Category::ACTIVE, 'type' => Category::OUTCOME_CATEGORY])
            ->orderBy('activity DESC')
            ->all();

        return $this->render('category', [
            'user' => $user,
            'model' => $model,
            'outcome' => $outcome,
            'income' => $income,
            'categories' => Category::CATEGORIES
        ]);
    }

    public function actionWallet()
    {
        $model = new Wallet();
        $user = User::findOne(Yii::$app->getUser()->id);

        if(Yii::$app->request->isPost){
            if ($model->load(Yii::$app->request->post()) && $model->validate()){
                $model->save();
                $this->refresh();
            }
        }

        $walletList = $user->getWallets()
            ->where(['active' => Wallet::ACTIVE])
            ->all();


        return $this->render('wallet', [
            'user' => $user,
            'model' => $model,
            'walletList' => $walletList,
            'currencies' => Wallet::CURRENCIES
        ]);
    }

    public function actionSettings ()
    {
        $user = User::findOne(Yii::$app->user->id);
        $changePassword = new ChangePasswordForm();

        if(Yii::$app->request->isAjax){
            $file = UploadedFile::getInstanceByName('file');

            $extension = explode('/',$file->type);
            $extension = strtolower(end($extension));
            $key = md5(uniqid());
            $fileName = "{$key}.{$extension}";

            $publicUrl = Yii::$app->clientS3->uploadAvatar($fileName, $file->tempName);

            if(!empty($user->avatarUrl)) {
                Yii::$app->clientS3->deleteAvatar($user->avatarUrl);
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
            if($changePassword->load(Yii::$app->request->post()) && $changePassword->validate()){
                $changePassword->setPassword();
                $this->refresh();
            }
        }

        return $this->render('settings', [
            'user' => $user,
            'password' => $changePassword,
        ]);
    }

}
