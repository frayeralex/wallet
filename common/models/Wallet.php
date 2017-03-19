<?php


namespace common\models;

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
    const CURRENCIES = ["UA", "USD", "EUR"];


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

            ['currency', 'default', 'value' => self::CURRENCIES[0]],
            ['currency', 'in', 'range' => self::CURRENCIES],

            ['value', 'double'],
            ['value', 'default', 'value' => 0],
        ];
    }
}