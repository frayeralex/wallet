<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;
use frontend\assets\SiteWalletAsset;
use frontend\assets\PdfAsset;

SiteWalletAsset::register($this);
PdfAsset::register($this);
$this->params['user'] = $user;

?>
<div class="site-wallet">
    <div class="top-sidebar">
        <h1 class="page-title"><?= Yii::t('app', 'Wallet page') ?></h1>
        <ul class="btn-group btn-group-lg ">
            <li class="btn btn-default"><div data-toggle="modal" class="add-wallet-btn" data-target="#addWallet"></div></li>
            <?php if (count($walletList)) : ?>
            <li class="btn btn-default"><div data-toggle="modal" class="wallet-transaction" data-target="#walletTransaction"></div></li>
            <li class="btn btn-default"><div data-toggle="modal" class="wallet-report-pdf" data-target="#pdfModal"></div></>
            <?php endif; ?>
        </div>
    </div>
    <?php if (count($walletList)) : ?>
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
    <?php endif; ?>
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


<!-- Modal pdf -->
<div class="modal fade" id="pdfModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?= Yii::t('app', 'Create wallet report')?></h4>
            </div>
            <form id="pdf_wallet_form">
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?= Yii::t('app' , 'Wallet') ?></label>
                            <select name="wallet_from" id="pdf_wallet_id" class="form-control">
                                <?php  foreach ($walletList as $item): ?>
                                    <option value="<?= $item->id ?>"><?= "{$item->name} ({$item->currency})"?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('app', 'From') ?></label>
                            <input type="date" class="form-control" id="pdf_date_from">
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('app', 'To') ?></label>
                            <input type="date" class="form-control" id="pdf_date_to">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success"><?= Yii::t('app', 'Generate PDF') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                    </div>
                </div>
            </form>
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
