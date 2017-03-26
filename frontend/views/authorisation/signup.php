<?php

use yii\bootstrap\ActiveForm;

?>
<div class="login-wrap">

    <h1><?= Yii::t('app', 'Registration form') ?></h1>
    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>


            <div class="form-group">
                <button type="submit" class="btn btn-success">
                    <?= Yii::t('app', 'Submit') ?>
                </button>
                <a href="<?= Yii::$app->urlManager->createUrl(['authorisation/index'])?>" class="btn btn-primary">
                    <?= Yii::t('app', 'Login') ?>
                </a>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
