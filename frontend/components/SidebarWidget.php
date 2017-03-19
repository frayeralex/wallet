<?php


namespace frontend\components;

use Yii;
use yii\base\Widget;
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
            $options = [
                'href' => $item["url"],
            ];
            $html .= Html::beginTag('li', ['class' => 'social-item-link']);;
            $html .= Html::beginTag('a', $options);
            $html .= $item['label'];
            $html .= Html::endTag('a');
            $html .= Html::endTag('li');
        }
        $html .= Html::endTag('ul');

        return $html;
    }
}