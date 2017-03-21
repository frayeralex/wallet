<?php


namespace console\controllers;

use common\models\Wallet;
use console\models\Rate;
use yii\console\Controller;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\Json;
use yii\httpclient\Client;


class RateController extends Controller
{
    const OPTIONS = [
        'requestConfig' => [
            'format' => Client::FORMAT_JSON
        ],
        'responseConfig' => [
            'format' => Client::FORMAT_JSON
        ],
    ];

    public $date;

    public function options()
    {
        return ['date'];
    }

    public function optionAliases()
    {
        return ['d' => 'date'];
    }

    public function actionIndex()
    {
        $url = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json';
        $client = new Client(self::OPTIONS);
        $response = $client->createRequest()
            ->setUrl($url)
            ->setMethod('get')
            ->send();

        if ($response->isOk) {

            $filter = ArrayHelper::index($response->getData(), 'cc');
            $filter = ArrayHelper::filter($filter, Wallet::CURRENCIES);
            if(!$filter) {
                echo "No data to save \n";
                return 1;
            }

            foreach ($filter as $item){
                $cc = ArrayHelper::getValue($item, 'cc');
                $label = ArrayHelper::getValue($item, 'txt');
                $date = date("Y-m-d", time());
                $value = ArrayHelper::getValue($item, 'rate');

                $rate = Rate::findOne(['cc' => $cc, 'exchangedate' => $date]);

                if(!$rate) $rate = new Rate();

                $rate->setAttribute('cc', $cc);
                $rate->setAttribute('label', $label);
                $rate->setAttribute('exchangedate', $date);
                $rate->setAttribute('value', $value);
                $rate->save();
            }
            echo "=======Rates added success=========== \n";
            return 0;
        }
    }

    public function actionDate()
    {
        $url = 'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?date='.$this->date.'&json';
        $client = new Client(self::OPTIONS);

        $response = $client->createRequest()
            ->setUrl($url)
            ->setMethod('get')
            ->send();

        if ($response->isOk) {

            $filter = ArrayHelper::index($response->getData(), 'cc');
            $filter = ArrayHelper::filter($filter, Wallet::CURRENCIES);
            if(!$filter) {
                echo "No data to save from ".$this->date." date \n";
                return 1;
            }

            foreach ($filter as $item){
                $cc = ArrayHelper::getValue($item, 'cc');
                $label = ArrayHelper::getValue($item, 'txt');
                $date = \DateTime::createFromFormat("d.m.Y", ArrayHelper::getValue($item, 'exchangedate'));
                $date = $date->format('Y-m-d');
                $value = ArrayHelper::getValue($item, 'rate');

                $rate = Rate::findOne(['cc' => $cc, 'exchangedate' => $date]);

                if(!$rate) $rate = new Rate();

                $rate->setAttribute('cc', $cc);
                $rate->setAttribute('label', $label);
                $rate->setAttribute('exchangedate', $date);
                $rate->setAttribute('value', $value);
                $rate->save();
            }
            echo "=======Rates of ".$this->date." date added success=========== \n";
            return 0;
        }
    }
}