<?php


namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Income
 * @package common\models
 *
 * Income model
 *
 * @property integer $id
 * @property string $title
 * @property double $value
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property integer $userId
 * @property integer $categoryId
 * @property integer $walletId
 */
class Income extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%income}}';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['title', 'string', 'min' => 2, 'max' => 50],
            ['value', 'required'],
            ['value', 'double'],
            [['value', 'title', 'categoryId', 'walletId'], 'safe'],
        ];
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)){
            if($insert){
                $this->setAttribute('userId', Yii::$app->getUser()->id);
                $this->setAttribute('createdAt', date(DATE_ATOM, time()));
                $this->setAttribute('updatedAt', date(DATE_ATOM, time()));
            }else{
                $this->setAttribute('updatedAt', date(DATE_ATOM, time()));
            }
            return true;
        }
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId']);
    }

    public function getWallet()
    {
        return $this->hasOne(Wallet::className(), ['id' => 'walletId']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}