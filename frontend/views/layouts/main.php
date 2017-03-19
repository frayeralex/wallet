<?php

use yii\helpers\Html;
use frontend\components\SidebarWidget;
use frontend\assets\AppAsset;
use frontend\assets\FAAsset;

FAAsset::register($this);
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div id="app" class="app">
    <header class="app-header">
        <div class="logo">Wallet</div>
    </header>
    <aside class="main-sidebar">
        <div class="user-info">
            <div class="avatar-wrap">
                <img src="/img/user.png" alt="">
            </div>
            <span class="name">Admin Admin</span>
        </div>
        <nav class="sidebar-nav">
            <?= SidebarWidget::widget(['items' => [
                ['label' => 'Analytic', 'url' => '/site/index'],
                ['label' => 'Wallet', 'url' => '/site/wallet'],
                ['label' => 'Category', 'url' => '/site/category'],
                ['label' => 'Transactions', 'url' => '/site/income'],
                ['label' => 'Settings', 'url' => '/site/income'],
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
