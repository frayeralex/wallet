<?php

use yii\helpers\Html;;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use frontend\assets\SiteOutcomeAsset;

SiteOutcomeAsset::register($this);

$this->params['user'] = $user;
?>
<div class="outcome-page">
    <h1>
        <?= Yii::t('app', 'Outcomes') ?>
        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addOutcome"><?= Yii::t('app', 'Add +') ?></button>
    </h1>

    <?php if(!!count($wallets) && !!count($categories)) : ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th><?= Yii::t('app', 'Title') ?></th>
                    <th><?= Yii::t('app', 'Category') ?></th>
                    <th><?= Yii::t('app', 'Wallet') ?></th>
                    <th><?= Yii::t('app', 'Actual value') ?></th>
                    <th><?= Yii::t('app', 'Create') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($transactions as $item): ?>
                    <tr class="outcome-item" data-id="<?= $item->id?>">
                        <td class="title"><?= Html::encode($item->title) ?></td>
                        <td class="category"><?= Html::encode($item->category->name) ?></td>
                        <td class="wallet"><?= Html::encode($item->wallet->name) ?> (<?= Html::encode($item->wallet->currency) ?>)</td>
                        <td class="value"><?= Html::encode($item->value) ?> </td>
                        <td class="date" data-date="<?=$item->createdAt?>"><?= Yii::$app->formatter->asDate($item->createdAt) ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
    <nav class="pagination-wrap">
        <?= LinkPager::widget(['pagination' => $pagination])?>
    </nav>
    <!-- Modal -->
    <div class="modal fade" id="addOutcome" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Add outcome') ?></h4>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'add-outcome']); ?>
                <div class="modal-body">
                    <?= $form->field($model, 'title') ?>

                    <?= $form->field($model, 'value')->textInput(['type' => 'number', 'step'=> '0.01']) ?>

                    <div class="form-group">
                        <label><?= Yii::t('app', 'Category') ?></label>
                        <?= Html::activeDropDownList($model, 'categoryId', $categories, ['class' => 'form-control']) ?>
                    </div>

                    <div class="form-group">
                        <label><?= Yii::t('app', 'Wallet') ?></label>
                        <?= Html::activeDropDownList($model, 'walletId', $wallets, ['class' => 'form-control']) ?>
                    </div>
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
    <div class="modal fade" id="editOutcome" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Edit outcome')?></h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?= Yii::t('app', 'Title') ?></label>
                            <input type="text" class="form-control title">
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('app', 'Value') ?></label>
                            <input type="number" class="form-control value" step="0.01">
                        </div>
                        <div class="form-group">
                            <label><?= Yii::t('app', 'Date') ?></label>
                            <input type="date" class="form-control date">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success" id="updateOutcome"><?= Yii::t('app', 'Update') ?></button>
                        <button type="button" class="btn btn-danger" id="removeOutcome"><?= Yii::t('app', 'Remove') ?></button>
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php elseif (!count($wallets)) : ?>
    <div class="alert alert-warning" role="alert">
        <?= Yii::t('app', 'Firstly you must add wallet')?>
        <a href="<?= Yii::$app->urlManager->createUrl(["/site/wallet"])?>">
            <?= Yii::t('app', 'Please, go to wallet page')?>
        </a>
    </div>
    <?php elseif (!count($categories)) : ?>
    <div class="alert alert-warning" role="alert">
        <?= Yii::t('app', 'Firstly you must add outcome category')?>
        <a href="<?= Yii::$app->urlManager->createUrl(["/site/category"])?>">
            <?= Yii::t('app', 'Please, go to category page')?>
        </a>
    </div>
    <?php endif; ?>
</div>
