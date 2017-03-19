<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

?>
<div class="outcome-page">
    <h1>Outcomes</h1>

    <?php if(!!count($wallets) && !!count($categories)){ ?>
    <div class="row">
        <div class="col-md-12">
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
                    <tr class="outcome-item" data-outcome-id="<?= $item->id?>">
                        <td><?= Html::encode($item->title) ?></td>
                        <td><?= Html::encode($item->category->name) ?></td>
                        <td><?= Html::encode($item->wallet->name) ?></td>
                        <td><?= Html::encode($item->value) ?> (<?= Html::encode($item->wallet->currency) ?>)</td>
                        <td><?= Yii::$app->formatter->asDate($item->createdAt) ?></td>
                    </tr>
                <?php }?>
                </tbody>
            </table>
        </div>
    </div>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addOutcome">Add new</button>

    <!-- Modal -->
    <div class="modal fade" id="addOutcome" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add income category</h4>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'add-outcome']); ?>
                <div class="modal-body">
                    <?= $form->field($model, 'title') ?>

                    <?= $form->field($model, 'value')->textInput(['type' => 'number', 'step'=> '0.01']) ?>

                    <div class="form-group">
                        <label>Category</label>
                        <?= Html::activeDropDownList($model, 'categoryId', $categories, ['class' => 'form-control']) ?>
                    </div>

                    <div class="form-group">
                        <label>Wallet</label>
                        <?= Html::activeDropDownList($model, 'walletId', $wallets, ['class' => 'form-control']) ?>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Add category</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <?php } if(!count($wallets)) { ?>
        <div class="alert alert-warning" role="alert">
            <?= Yii::t('app', 'Firstly you must add wallet')?>
            <a href="<?= Url::toRoute(["/site/wallet"])?>">
                <?= Yii::t('app', 'Please, go to wallet page')?>
            </a>
        </div>
    <?php } if (!count($categories)) {?>
        <div class="alert alert-warning" role="alert">
            <?= Yii::t('app', 'Firstly you must add outcome category')?>
            <a href="<?= Url::toRoute(["/site/category"])?>">
                <?= Yii::t('app', 'Please, go to category page')?>
            </a>
        </div>
    <?php } ?>
</div>
