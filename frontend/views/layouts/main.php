<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use frontend\components\HeaderWidget;
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

<div id="app">
    <?php
    NavBar::begin([
        'brandLabel' => 'Wallet',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'Wallet', 'url' => ['/site/wallet']],
        ['label' => 'Category', 'url' => ['/site/category']],
        ['label' => 'income', 'url' => ['/site/income']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();

    ?>
    <div class="loader">Loading...</div>
    <div class="container">
        <aside class="main-nav">
            <?= HeaderWidget::widget(['items' => [
                ['label' => 'Home', 'url' => '/site/index'],
                ['label' => 'Wallet', 'url' => '/site/wallet'],
                ['label' => 'Category', 'url' => '/site/category'],
                ['label' => 'income', 'url' => '/site/income'],
                ['label' => 'income', 'url' => '/site/income'],
                ['label' => 'Logoute', 'url' => '/site/logout'],
            ]]) ?>
        </aside>
        <?= $content ?>
    </div>
</div>


<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
