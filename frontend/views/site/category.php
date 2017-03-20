<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;


?>
<div class="category-page">
    <h1>
        <th><?= Yii::t('app', 'Category page') ?></th>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addCategory">Add category</button>
    </h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Incomes</h2>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th><?= Yii::t('app', 'Name') ?></th>
                    <th><?= Yii::t('app', 'Created') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($income as $item){?>
                <tr class="category-item" data-catagory-id="<?= $item->id ?>">
                    <td class="name"><?= Html::decode($item->name) ?></td>
                    <td><?= Yii::$app->formatter->asDate($item->createdAt) ?></td>
                </tr>
                </tbody>
                <?php } ?>
            </table>
        </div>
        <div class="col-md-6">
            <h2>Outcomes</h2>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th><?= Yii::t('app', 'Name') ?></th>
                    <th><?= Yii::t('app', 'Created') ?></th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($outcome as $item){?>
                    <tr class="category-item" data-catagory-id="<?= $item->id ?>">
                        <td class="name"><?= $item->name ?></td>
                        <td class="type"><?= Yii::$app->formatter->asDate($item->createdAt) ?></td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addCategory" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Add category')?></h4>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'add-category']); ?>
                <div class="modal-body">
                    <?= $form->field($model, 'name') ?>

                    <div class="form-group">
                        <label><?= Yii::t('app', 'Category') ?></label>
                        <?= Html::activeDropDownList($model, 'type', ArrayHelper::index($categories, function($e){return $e;}), ['class' => 'form-control']) ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success"><?= Yii::t('app', 'Add category') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editCategory" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"><?= Yii::t('app', 'Edit category')?></h4>
                </div>
                <div class="modal-body">
                    <div class="modal-body">
                        <div class="form-group">
                            <label><?= Yii::t('app', 'Name') ?></label>
                            <input type="text" class="form-control category-name">
                        </div>
                    </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="updateCategory"><?= Yii::t('app', 'Update') ?></button>
                    <button type="button" class="btn btn-danger" id="removeCategory"><?= Yii::t('app', 'Remove') ?></button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?= Yii::t('app', 'Close') ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
