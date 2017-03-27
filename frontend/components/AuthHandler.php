<?php


namespace frontend\components;

use common\models\Auth;
use common\models\User;
use Yii;
use yii\authclient\ClientInterface;
use yii\helpers\ArrayHelper;

class AuthHandler
{
    /**
     * @var ClientInterface
     */
    private $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function handle()
    {
        $source = $this->client->getId();
        $email = null;
        $id = null;
        $avatarUrl = null;

        $attributes = $this->client->getUserAttributes();
        switch ($source){
            case "facebook":
            {
                $email = ArrayHelper::getValue($attributes, 'email');
                $id = ArrayHelper::getValue($attributes, 'id');
                $nickname = ArrayHelper::getValue($attributes, 'name');
            };
            break;
            case "google":
            {
                $email = ArrayHelper::getValue($attributes, 'emails.0.value');
                $id = ArrayHelper::getValue($attributes, 'id');
                $avatarUrl = ArrayHelper::getValue($attributes, 'image.url');
                $nickname = ArrayHelper::getValue($attributes, 'displayName');
            };break;
            case "vk":
            {
                $email = ArrayHelper::getValue($attributes, 'uid')."@vk.com";
                $id = ArrayHelper::getValue($attributes, 'id');
                $nickname = ArrayHelper::getValue($attributes, 'id');
                $avatarUrl = ArrayHelper::getValue($attributes, 'photo');
            };break;
            default:
                die("This social auth don`t maintain");
        }



        $auth = Auth::find()->where([
            'source' => $source,
            'source_id' => $id,
        ])->one();

        if (Yii::$app->user->isGuest) {
            if ($auth) { // login
                $user = $auth->user;
                Yii::$app->user->login($user, ArrayHelper::getValue(Yii::$app->params, 'user.rememberMeDuration', 3600 * 60));
            } else {
                if ($email !== null && User::find()->where(['email' => $email])->exists()) {
                    $user = User::findOne(['email' => $email]);
                    Yii::$app->user->login($user, ArrayHelper::getValue(Yii::$app->params, 'user.rememberMeDuration', 3600 * 60));

                } else {
                    $password = Yii::$app->security->generateRandomString(6);
                    $user = new User([
                        'username' => $nickname,
                        'email' => $email,
                        'password' => $password,
                        'avatarUrl' => $avatarUrl
                    ]);
                    $user->generateAuthKey();
                    $user->generatePasswordResetToken();

                    $transaction = User::getDb()->beginTransaction();

                    if ($user->save()) {
                        $auth = new Auth([
                            'user_id' => $user->id,
                            'source' => $this->client->getId(),
                            'source_id' => (string)$id,
                        ]);
                        if ($auth->save()) {
                            $transaction->commit();
                            Yii::$app->user->login($user, ArrayHelper::getValue(Yii::$app->params, 'user.rememberMeDuration', 3600 * 60));
                        } else {
                            Yii::$app->getSession()->setFlash('error', [
                                Yii::t('app', 'Unable to save {client} account: {errors}', [
                                    'client' => $this->client->getTitle(),
                                    'errors' => json_encode($auth->getErrors()),
                                ]),
                            ]);
                        }
                    } else {
                        Yii::$app->getSession()->setFlash('error', [
                            Yii::t('app', 'Unable to save user: {errors}', [
                                'client' => $this->client->getTitle(),
                                'errors' => json_encode($user->getErrors()),
                            ]),
                        ]);
                    }
                }
            }
        } else { // user already logged in
            if (!$auth) { // add auth provider
                $auth = new Auth([
                    'user_id' => Yii::$app->user->id,
                    'source' => $this->client->getId(),
                    'source_id' => (string)$attributes['id'],
                ]);
                if ($auth->save()) {
                    /** @var User $user */
                    $user = $auth->user;
                    $this->updateUserInfo($user);
                    Yii::$app->getSession()->setFlash('success', [
                        Yii::t('app', 'Linked {client} account.', [
                            'client' => $this->client->getTitle()
                        ]),
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('error', [
                        Yii::t('app', 'Unable to link {client} account: {errors}', [
                            'client' => $this->client->getTitle(),
                            'errors' => json_encode($auth->getErrors()),
                        ]),
                    ]);
                }
            } else { // there's existing auth
                Yii::$app->getSession()->setFlash('error', [
                    Yii::t('app',
                        'Unable to link {client} account. There is another user using it.',
                        ['client' => $this->client->getTitle()]),
                ]);
            }
        }
    }
}