<?php


namespace common\models;


use yii\db\ActiveRecord;

/**
 * Class Income
 * @package common\models
 *
 * Income model
 *
 * @property integer $id
 * @property string $title
 * @property integer $value
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
        ];
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'categoryId']);
    }
}