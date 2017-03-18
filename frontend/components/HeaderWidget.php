<?php


namespace frontend\components;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;


class HeaderWidget extends Widget
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
        $html = Html::beginTag('ul', ['class' => 'sidebar-nav']);
        if(Yii::$app->user->isGuest){
            $this->items =  ArrayHelper::filter($this->items, ['public' => true]);
        }

        foreach ($this->items as $item){
            $options = [
                'href' => $item["url"],
            ];
            $html .= Html::beginTag('li', ['class' => 'social-item-link']);;
            $html .= Html::beginTag('a', $options);
            $html .= $item['label'];
            $html .= '<i class="fa fa-facebook"></i>';
            $html .= Html::endTag('a');
            $html .= Html::endTag('li');
        }
        $html .= Html::endTag('ul');

        return $html;
    }
}