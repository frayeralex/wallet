<?php


namespace frontend\components;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;


class SidebarWidget extends Widget
{
    public $items;

    public function init()
    {
        parent::init();
        if ($this->items === null) {
            $this->items = [];
        }
    }

    public function run()
    {
        $html = Html::beginTag('ul');
        foreach ($this->items as $item){
            $html .= Html::beginTag('li', ['class' => ArrayHelper::getValue($item, 'class')]);;
            $html .= Html::beginTag('a', ['href' => ArrayHelper::getValue($item, 'url')]);
            $html .= ArrayHelper::getValue($item, 'label');
            $html .= Html::endTag('a');
            $html .= Html::endTag('li');
        }
        $html .= Html::endTag('ul');

        return $html;
    }
}