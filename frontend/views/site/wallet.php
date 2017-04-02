<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;
use frontend\assets\SiteWalletAsset;

SiteWalletAsset::register($this);
$this->params['user'] = $user;

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
                    <tr class="wallet-item" data-id="<?= $walletItem->id; ?>">
                        <td class="name"><?= Html::encode($walletItem->name) ?></td>
                        <td><?= Html::encode($walletItem->currency) ?></td>
                        <td class="value"><?= Html::encode($walletItem->value) ?></td>
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

<!-- Modal edit/remove -->
<div class="modal fade" id="editWallet" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Edit wallet')?></h4>
            </div>
            <div class="modal-body">
                <div class="modal-body">
                    <div class="form-group">
                        <label><?= Yii::t('app', 'Name') ?></label>
                        <input type="text" class="form-control name">
                    </div>
                    <div class="form-group">
                        <label><?= Yii::t('app', 'Value') ?></label>
                        <input type="number" class="form-control value" step="0.01">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="updateWallet"><?= Yii::t('app', 'Update') ?></button>
                    <button type="button" class="btn btn-danger" id="removeWallet"><?= Yii::t('app', 'Remove') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal transactions-->
<div class="modal fade" id="walletTransaction" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Wallet transfers') ?></h4>
            </div>
            <form>
            <div class="modal-body">
                <div class="">
                    <div class="rate row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'From') ?></label>
                                <select name="wallet_from" id="wallet_from" class="form-control">
                                <?php  foreach ($walletList as $item): ?>
                                    <option data-currency="<?= $item->currency ?>"
                                            data-value="<?= $item->value ?>"
                                            value="<?= $item->id ?>"><?= "{$item->name} ({$item->currency})"?></option>
                                <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="wallet-transaction"></div>
                        </div>
                        <div class="col-md-5">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'To') ?></label>
                                <select name="wallet_to" id="wallet_to" class="form-control">
                                    <?php  foreach ($walletList as $item): ?>
                                        <option data-currency="<?= $item->currency ?>"
                                                data-value="<?= $item->value ?>"
                                                value="<?= $item->id ?>"><?= "{$item->name} ({$item->currency})"?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'Transfer sum') ?></label>
                                <input type="number"
                                       class="form-control"
                                       step="0.01"
                                       id="value_from">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'Enter rate') ?></label>
                                <input type="number"
                                       class="form-control"
                                       step="0.01"
                                       id="value_rate">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?= Yii::t('app' , 'Result (1 * rate)') ?></label>
                                <input type="number"
                                       class="form-control"
                                       step="0.01"
                                       disabled="true"
                                       id="value_to">
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success"><?= Yii::t('app', 'Apply') ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
            </div>
            </form>
        </div>
    </div>
</div>
