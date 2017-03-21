<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Rate
 * @package common\models
 *
 * @property integer $id
 * @property string $cc
 * @property double $value
 * @property string $label
 * @property date $exchangedate
 */
class Rate extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%rate}}';
    }

    public function rules()
    {
        return [
            [['cc', 'value', 'exchangedate'], 'required'],
            [['cc', 'label'], 'string'],
            [['value'], 'double'],
            ['cc', 'in', 'range' => Wallet::CURRENCIES],
        ];
    }
}