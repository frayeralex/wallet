<?php

use yii\helpers\Html;

$this->title = 'Wallet';

?>
<div class="analytic-page">
    <h1>Hello world</h1>
    <div class="chart-grid">
        <section class="flex-item">
            <h2><?= Yii::t('app', 'Incomes') ?></h2>
            <div id="incomePieChart" class="chart-wrap">
                <div class="loader fast light"></div>
            </div>
        </section>
        <section class="flex-item">
            <h2><?= Yii::t('app', 'Outcomes') ?></h2>
            <div id="outcomePieChart" class="chart-wrap">
                <div class="loader fast light"></div>
            </div>
        </section>
        <section class="flex-item big">
            <h2><?= Yii::t('app', 'Last 7 days') ?></h2>
            <div class="form-group col-md-4">
                <?= Html::dropDownList('currency', '0', $currencies, ['class' => 'form-control', 'id' => 'currencySelect']) ?>
            </div>
            <div id="lastTransactionsColumnChart" class="chart-wrap">
                <div class="loader fast light"></div>
            </div>
        </section>
        <section class="flex-item big">
            <h2><?= Yii::t('app', 'Rates') ?></h2>
            <div id="ratesLinearChart" class="chart-wrap">
                <div class="loader fast light"></div>
            </div>
        </section>
    </div>
</div>
