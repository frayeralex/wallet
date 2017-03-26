<?php


namespace frontend\components;


use yii\base\Component;
use yii\httpclient\Client;
use yii\helpers\ArrayHelper;

class DeclarationSearcher extends Component
{
    const NAZK_URL = 'https://public-api.nazk.gov.ua/v1/declaration/?q=';
    const OPTIONS = [
        'requestConfig' => [
            'format' => Client::FORMAT_JSON
        ],
        'responseConfig' => [
            'format' => Client::FORMAT_JSON
        ],
    ];

    static public function findByWords($text)
    {
        if(!$text) return;
        $query = '';
        $words = preg_split("/[\s]+/", $text);
        foreach ($words as $word){
            $query .= $word . '+';
        }
        $url = self::NAZK_URL.$query;

        $client = new Client(self::OPTIONS);
        $response = $client->createRequest()
            ->setUrl($url)
            ->setMethod('get')
            ->send();
        if ($response->isOk){
            $items = ArrayHelper::getValue($response->getData(), 'items');
            if(!count($items)) return [];

            return $items;
        }
    }
}