<?php


namespace console\controllers;

use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Json;
use yii\httpclient\Client;


class CurrencyController extends Controller
{
    public $message;

    public function options()
    {
        return ['message'];
    }

    public function optionAliases()
    {
        return ['m' => 'message'];
    }

    public function actionIndex()
    {
        $client = new Client();
        $response = $client->createRequest()
            ->setFormat(Client::FORMAT_JSON)
            ->setMethod('get')
            ->setUrl('https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json')
            ->send();
        if ($response->isOk) {
            var_dump($response->getData());
        }
    }
}