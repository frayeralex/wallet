<?php


namespace frontend\components\SidebarWidget;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


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
            $activeClass = ArrayHelper::getValue($item, 'url') === Url::current() ? ' active' : '';

            $html .= Html::beginTag('li', ['class' => ArrayHelper::getValue($item, 'class') . $activeClass]);
            $html .= Html::beginTag('a',
                ['href' => Yii::$app->urlManager->createUrl(ArrayHelper::getValue($item, 'url'))]);
            $html .= ArrayHelper::getValue($item, 'label');
            $html .= Html::endTag('a');
            $html .= Html::endTag('li');
        }
        $html .= Html::endTag('ul');

        return $html;
    }
}