<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;
use frontend\assets\SiteWalletAsset;

SiteWalletAsset::register($this);
$this->params['user'] = $user;
$wallets = ArrayHelper::map(ArrayHelper::toArray($walletList), 'id', 'name');
?>
<div class="site-wallet">
    <h1><?= Yii::t('app', 'Wallet page') ?></h1>
    <aside class="controls-panel">
        <div data-toggle="modal" class="add-wallet-btn" data-target="#addWallet"></div>
        <div data-toggle="modal" class="wallet-transaction" data-target="#walletTransaction"></div>
        <div class="wallet-report-pdf"></div>
    </aside>
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

<!-- Modal -->
<div class="modal fade" id="walletTransaction" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Wallet transfers') ?></h4>
            </div>
            <div class="modal-body">
                <div class="">
                    <div class="rate row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'From') ?></label>
                                <?= Html::dropDownList('wallet_from', null,$wallets, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="wallet-transaction"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'To') ?></label>
                                <?= Html::dropDownList('wallet_to_name', null,$wallets, ['class' => 'form-control']) ?>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <?= Html::input('number','wallet_from_value', null, [
                                        'class' => 'form-control',
                                        'step' => '0.01'
                                ])?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <?= Html::input('number','wallet_from_value', null, [
                                        'class' => 'form-control',
                                        'step' => '0.01'])
                                ?>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <?= Html::input('number','wallet_to_value', null,[
                                        'class' => 'form-control',
                                        'step' => '0.01',
                                        'disabled' => true]
                                )?>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?= Yii::t('app', 'Apply') ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>
