<?php


namespace common\models;

use Yii;
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
 * @property integer $active
 * @property integer $activity
 * @property integer $createdAt
 * @property integer $updatedAt
 * @property integer $userId
 */
class Category extends ActiveRecord
{
    const ACTIVE = 1;
    const DISACTIVE = 0;
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

            ['active', 'default', 'value' => self::ACTIVE],
            ['active', 'in', 'range' => [self::ACTIVE, self::DISACTIVE]],
            ['type', 'default', 'value' => self::OUTCOME_CATEGORY],
            ['type', 'in', 'range' => self::CATEGORIES]
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

    public function getIncomes()
    {
        return $this->hasMany(Income::className(), ['categoryId' => 'id']);
    }

    public function getOutcomes()
    {
        return $this->hasMany(Outcome::className(), ['categoryId' => 'id']);
    }
}