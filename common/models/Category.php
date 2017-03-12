<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Category
 * @package common\models
 *
 * Category model
 *
 * @property integer $id
 * @property string $name
 * @property string $type
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property integer $userId
 */
class Category extends ActiveRecord
{
    const OUTCOME_CATEGORY = 'outcome';
    const INCOME_CATEGORY = 'income';
    const CATEGORIES = [self::INCOME_CATEGORY, self::OUTCOME_CATEGORY];

    public static function tableName()
    {
        return '{{%category}}';
    }

    public function rules()
    {
        return [
            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'min' => 2, 'max' => 50],

            ['type', 'default', 'value' => self::OUTCOME_CATEGORY],
            ['type', 'in', 'range' => self::CATEGORIES]
        ];
    }

    public function getIncomes()
    {
        return $this->hasMany(Income::className(), ['categoryId' => 'id']);
    }
}