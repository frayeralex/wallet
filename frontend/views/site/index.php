<?php

use yii\helpers\Html;

$this->title = 'Wallet analytic';

$this->params['user'] = $user;
?>
<div class="analytic-page">
    <?php if ($currencies) : ?>
    <div class="top-sidebar">
        <h1 class="page-title"><?= Yii::t('app', 'Analytic page') ?></h1>
        <aside class="btn-group btn-group-lg currency-control" id="currency-control" data-currency="<?= $currencies[0] ?>">
            <?php  foreach ($currencies as $index => $item): ?>
                <button class="btn <?= $index === 0 ? 'btn-info' : 'btn-default' ?>" data-value="<?= $index ?>"><?= Html::encode($item) ?></button>
            <?php endforeach ?>
        </aside>
    </div>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#incomeTab" aria-controls="home" data-chart="foo" role="tab" data-toggle="tab">
                    <?= Yii::t('app', 'Incomes') ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#outcomeTab" aria-controls="profile" role="tab" data-toggle="tab">
                    <?= Yii::t('app', 'Outcomes') ?>
                </a>
            </li>
            <li role="presentation">
                <a href="#lastTab" aria-controls="messages" role="tab" data-toggle="tab">
                    <?= Yii::t('app', 'Last transactions') ?>
                </a>
            </li>
        </ul>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane fade in active" id="incomeTab">
                <div id="incomePieChart" class="chart-wrap">
                    <div class="loader fast light"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="outcomeTab">
                <div id="outcomePieChart" class="chart-wrap">
                    <div class="loader fast light"></div>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane fade" id="lastTab">
                <div id="lastTransactionsColumnChart" class="chart-wrap">
                    <div class="loader fast light"></div>
                </div>
            </div>
        </div>
    </div>
    <?php else : ?>
        <div class="alert alert-info" role="alert">
            <h1><?= Yii::t('app', 'Hello {user}! Welcome to the wallet app!', ['user' => $user->username])?></h1>
            <h4><?= Yii::t('app', 'Now you don`t need to keep your many traffic in you head!')?></h4>
            <h4><?= Yii::t('app', 'Just add yor first wallet and categories to start!')?></h4>
            <h4><?= Yii::t('app', 'For few month you can analyse you incomes/outcomes with charts.')?></h4>
            <h4><?= Yii::t('app', 'if you wish You can print or save you finance data.')?></h4>
        </div>
    <?php endif; ?>
</div>
