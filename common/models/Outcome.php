<?php


namespace common\models;


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
 * @property integer $createdAt
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
}