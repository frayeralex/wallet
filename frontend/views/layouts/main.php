<?php

use yii\helpers\Html;
use frontend\widgets\SidebarWidget;
use frontend\widgets\CurrencyRateWidget;
use frontend\assets\AppAsset;
use frontend\assets\FAAsset;
use common\models\Wallet;

FAAsset::register($this);
AppAsset::register($this);

$user = $this->params['user'];
$avatarUrl = $user && $user->avatarUrl ? $user->avatarUrl : '/img/user.png';
$userName = $user ? $user->username : Yii::t('app', 'Guest');
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="app" class="app">
    <header class="app-header">
        <div class="rate-widget-block">
            <?= CurrencyRateWidget::widget() ?>
        </div>
        <div class="rate-widget-block">
            <?= CurrencyRateWidget::widget(['currency' => Wallet::CURRENCIES[2]]) ?>
        </div>
        <div class="rate-widget-block">
            <?= CurrencyRateWidget::widget(['currency' => Wallet::CURRENCIES[3]]) ?>
        </div>
    </header>
    <aside class="main-sidebar">
        <div class="user-info">
            <div class="avatar-wrap">
                <img src="<?= $avatarUrl ?>" id="avatar" alt="avatar">
            </div>
            <span class="name"><?= $userName ?></span>
        </div>
        <nav class="sidebar-nav">
            <?= SidebarWidget::widget(['items' => [
                ['label' => 'Analytic', 'url' => '/site/index', 'class' => 'analytic'],
                ['label' => 'Wallet', 'url' => '/site/wallet', 'class' => 'wallet'],
                ['label' => 'Category', 'url' => '/site/category', 'class' => 'category'],
                ['label' => 'Income', 'url' => '/site/income', 'class' => 'income'],
                ['label' => 'Outcome', 'url' => '/site/outcome', 'class' => 'outcome'],
                ['label' => 'Settings', 'url' => '/site/settings', 'class' => 'settings'],
            ]]) ?>
        </nav>
        <div class="logout-block">
            <button type="button" class="logout-btn">Exit</button>
        </div>
    </aside>
    <main class="content">
        <div class="content-area">
            <?= $content ?>
        </div>
    </main>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
