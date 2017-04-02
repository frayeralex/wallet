<?php


namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Class Wallet
 * @package common\models
 *
 * Wallet model
 * @property integer $id
 * @property string $name
 * @property string $currency
 * @property double $value
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property integer $userId
 */
class Wallet extends ActiveRecord
{
    const ACTIVE = 1;
    const DISACTIVE = 0;
    const CURRENCIES = ["UAH", "USD", "EUR", "RUB"];


    public static function tableName()
    {
        return '{{%wallet}}';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 50],

            ['active', 'default', 'value' => self::ACTIVE],
            ['active', 'in', 'range' => [self::ACTIVE, self::DISACTIVE]],

            ['currency', 'default', 'value' => self::CURRENCIES[0]],
            ['currency', 'in', 'range' => self::CURRENCIES],

            ['value', 'double'],
            ['value', 'filter', 'filter' => 'intval'],
            ['value', 'default', 'value' => 0],

            [['name', 'value'], 'safe'],
        ];
    }

    public function getLabel(){
        return "{$this->name} ({$this->currency})";
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

    public function getIncomes()
    {
        return $this->hasMany(Income::className(), ['walletId' => 'id']);
    }

    public function getOutcomes()
    {
        return $this->hasMany(Outcome::className(), ['walletId' => 'id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'userId']);
    }
}