<?php


namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;

class ChangePasswordForm extends Model
{
    public $password;
    public $newPassword;
    public $repeatPassword;

    private $user;

    public function rules()
    {
        return [
            ['password', 'required', 'message' => Yii::t('app', 'Enter your current password before change')],
            ['password', 'validatePassword'],
            ['password', 'string', 'min' => 6, 'max' => 40, 'message' => Yii::t('app', 'Password should be 6 or more characters')],
            ['newPassword', 'required', 'message' => Yii::t('app', 'Enter new password')],
            ['repeatPassword', 'required', 'message' => Yii::t('app', 'Repeat password')],
            ['newPassword', 'string', 'min' => 6, 'max' => 40,],
            ['repeatPassword', 'compare', 'compareAttribute'=> 'newPassword']
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => Yii::t('app', 'Old password'),
            'newPassword' => Yii::t('app', 'Enter new password'),
            'repeatPassword' => Yii::t('app', 'Repeat new password'),
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (!$this->getUser()->validatePassword($this->password)) {
                $this->addError($attribute, Yii::t('app', 'Incorrect password'));
            }
        }
    }

    public function setPassword()
    {
        $this->getUser()->setPassword($this->newPassword);
        $this->getUser()->save();
    }

    /**
     * Get user by id
     *
     * @return static
     */
    protected function getUser()
    {
        if ($this->user === null) {
            $this->user = User::findOne(Yii::$app->user->id);
        }

        return $this->user;
    }
}