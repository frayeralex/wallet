<?php

use yii\bootstrap\ActiveForm;

$this->title = 'Login';
?>
<div class="login-wrap">
    <h1><?= Yii::t('app', 'Welcome to wallet') ?></h1>

    <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

        <?= $form->field($model, 'username') ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?= $form->field($model, 'rememberMe')->checkbox() ?>

        <div class="form-group">
            <button type="submit" class="btn btn-success">
                <?= Yii::t('app', 'Login') ?>
            </button>
            <a href="<?= Yii::$app->urlManager->createUrl(['auth/signup'])?>" class="btn btn-primary">
                <?= Yii::t('app', 'Registration') ?>
            </a>
        </div>
    <?php ActiveForm::end(); ?>

    <div class="socials-auth">
        <p><?= Yii::t('app', 'Or login by social') ?></p>
        <?= yii\authclient\widgets\AuthChoice::widget([
            'baseAuthUrl' => ['auth/auth'],
            'popupMode' => true,
        ]) ?>
    </div>

</div>
