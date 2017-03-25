<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;

$this->params['user'] = $user;

?>
<div class="site-wallet">
    <h1><?= Yii::t('app', 'Wallet page') ?></h1>
    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addWallet"><?= Yii::t('app', 'Add +') ?></button>

    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th><?= Yii::t('app', 'Name') ?></th>
                    <th><?= Yii::t('app', 'Currency') ?></th>
                    <th><?= Yii::t('app', 'Actual value') ?></th>
                </tr>
                </thead>
                <tbody>
                    <?php  foreach ($walletList as $walletItem): ?>
                    <tr class="wallet-item" data-item-id="<?= $walletItem->id; ?>">
                        <td><?= Html::encode($walletItem->name) ?></td>
                        <td><?= Html::encode($walletItem->currency) ?></td>
                        <td><?= Html::encode($walletItem->value) ?></td>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="chart-grid">
        <section class="flex-item big">
            <h2><?= Yii::t('app', 'Currency rates') ?></h2>
            <div id="ratesLinearChart" class="chart-wrap">
                <div class="loader fast light"></div>
            </div>
        </section>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addWallet" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Add wallet') ?></h4>
            </div>
            <?php $form = ActiveForm::begin(['id' => 'wallet']); ?>
            <div class="modal-body">
                <?= $form->field($model, 'name') ?>
                <div class="form-group">
                    <label>Currency</label>
                    <?= Html::activeDropDownList($model, 'currency', ArrayHelper::index($currencies, function($e){return $e;}), ['class' => 'form-control']) ?>
                </div>
                <?= $form->field($model, 'value')->textInput(['type' => 'number', 'step'=> '0.01']) ?>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?= Yii::t('app', 'Add') ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
