<?php


namespace frontend\components;


use yii\httpclient\Client;
use yii\helpers\ArrayHelper;

class DeclarationSearcher
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
        $result = [];
        if(!$text) return $result;

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
            $result = ArrayHelper::getValue($response->getData(), 'items', []);
        }
        return $result;
    }
}