<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

?>
<div class="site-signup">

    <p>Please fill out the following fields to signup:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'firstName')->textInput() ?>

                <?= $form->field($model, 'lastName')->textInput() ?>

                <?= $form->field($model, 'phone')->input('number') ?>

                <?= $form->field($model, 'email') ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <div class="form-group">
                    <?= Html::submitButton('Signup', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('Signup', ['authorisation']) ?>.
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
