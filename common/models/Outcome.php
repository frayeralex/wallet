<?php


namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Outcome
 * @package common\models
 *
 * Outcome model
 *
 * @property integer $id
 * @property string $title
 * @property double $value
 * @property date $createdAt
 * @property integer $updatedAt
 * @property integer $userId
 * @property integer $categoryId
 * @property integer $walletId
 */
class Outcome extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%outcome}}';
    }

    public function rules()
    {
        return [
            ['title', 'required'],
            ['title', 'trim'],
            ['title', 'string', 'min' => 2, 'max' => 50],
            ['value', 'required'],
            ['value', 'filter', 'filter' => 'intval'],
            ['value', 'double'],
            ['createdAt', 'filter', 'filter' => function($val){
                return Yii::$app->formatter->asDate($val, 'Y-MM-dd HH:i:s');
            }],
            [['value', 'title', 'categoryId', 'walletId', 'createdAt'], 'safe'],
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