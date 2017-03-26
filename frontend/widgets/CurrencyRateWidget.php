<?php


namespace frontend\widgets;


use common\models\Rate;
use common\models\Wallet;
use yii\base\Widget;
use yii\helpers\Html;

class CurrencyRateWidget extends Widget
{
    public $currency;
    public $rates;

    public function init()
    {
        parent::init();
        if($this->currency === null){
            $this->currency = Wallet::CURRENCIES[1];
        }

        $this->rates = Rate::find()
            ->where(['cc' => $this->currency])
            ->limit(2)
            ->orderBy('exchangedate DESC')
            ->all();
    }

    public function run()
    {
        $yesterdayRate = $this->rates[1];
        $todayRate = $this->rates[0];
        $isGrow = $todayRate->value > $yesterdayRate->value;
        $isEqual = $todayRate->value === $yesterdayRate->value;

        $currency = Html::tag('span', $this->currency, ['class' => 'currency']);
        $value = Html::tag('span', $todayRate->value, ['class'=> 'rate']);
        $icon = Html::tag('span', '', [
            'class' => $isEqual ? 'equal' : $isGrow ? 'grow-up' : 'grow-down'
        ]);


        $container = Html::tag('div', $currency. $icon . $value,['class' => 'rate-widget']);
        return $container;
    }
}