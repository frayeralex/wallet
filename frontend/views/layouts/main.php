<?php

use yii\helpers\Html;
use frontend\components\SidebarWidget;
use frontend\assets\AppAsset;
use frontend\assets\FAAsset;

FAAsset::register($this);
AppAsset::register($this);

$user = $this->params['user'];
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
        <div class="logo">Wallet</div>
        <div class="global-search" id="global-search">
            <div class="input-wrap">
                <input type="search">
                <div class="loader"></div>
            </div>
            <ul class="search-results" id="search-results">
            </ul>
        </div>
    </header>
    <aside class="main-sidebar">
        <div class="user-info">
            <div class="avatar-wrap">
                <img src="<?= $user && $user->avatarUrl ? $user->avatarUrl : '/img/user.png' ?>" id="avatar" alt="avatar">
            </div>
            <span class="name"><?= $user ? $user->username : 'Guest'?></span>
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
