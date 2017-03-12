<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

?>
<div class="site-contact">
    <h1>Incomes</h1>


    <div class="row">
        <div class="col-md-8">>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Wallet</th>
                    <th>Actual value</th>
                    <th>Create</th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($transactions as $item){?>
                    <tr class="wallet-item">
                        <td><?= $item->title ?></td>
                        <td><?= ArrayHelper::getValue($categories, $item->categoryId)  ?></td>
                        <td><?= ArrayHelper::getValue($wallets, $item->walletId) ?></td>
                        <td><?= $item->value ?></td>
                        <td><?= Yii::$app->formatter->asDate($item->createdAt) ?></td>
                        <td>
                    </tr>
                <?php } var_dump($wallets)?>
                </tbody>
            </table>
        </div>
        <div class="col-md-4">
            <?php $form = ActiveForm::begin(['id' => 'income-form']); ?>

            <?= $form->field($model, 'title') ?>

            <?= $form->field($model, 'value')->input('number') ?>

            <div class="form-group">
                <label>Category</label>
                <?= Html::activeDropDownList($model, 'categoryId', $categories, ['class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <label>Wallet</label>
                <?= Html::activeDropDownList($model, 'walletId', $wallets, ['class' => 'form-control']) ?>
            </div>

            <div class="form-group">
                <?= Html::submitButton('Add record', ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

</div>
