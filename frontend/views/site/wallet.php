<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;


?>
<div class="site-wallet">
    <h1>Wallet page</h1>

    <div class="row">
        <div class="col-sm-8">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Wallet name</th>
                    <th>Currency</th>
                    <th>Actual value</th>
                    <th>Create</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                    <?php  foreach ($walletList as $walletItem){?>
                    <tr class="wallet-item">
                        <td><?= $walletItem->name ?></td>
                        <td><?= $walletItem->currency ?></td>
                        <td><?= $walletItem->value ?></td>
                        <td><?= Yii::$app->formatter->asDate($walletItem->createdAt) ?></td>
                        <td>
                            <button class="btn btn-danger" data-wallet-id="<?= $walletItem->id; ?>">
                                <span class="glyphicon glyphicon-remove"></span>
                            </button>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <div class="col-sm-4">
            <?php $form = ActiveForm::begin(['id' => 'form-create-wallet']); ?>

            <?= $form->field($model, 'name') ?>
            <div class="form-group">
                <label>Currency</label>
                <?= Html::activeDropDownList($model, 'currency', ArrayHelper::index($currencies, function($e){return $e;}), ['class' => 'form-control']) ?>
            </div>

            <?= $form->field($model, 'value')->textInput(['type' => 'number', 'step'=> '0.01']) ?>

            <div class="form-group">
                <?= Html::submitButton('Create wallet', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
