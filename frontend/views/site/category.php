<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use \yii\bootstrap\ActiveForm;


?>
<div class="site-wallet">
    <h1>Category page</h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Incomes</h2>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Create</th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($income as $item){?>
                <tr>
                    <td><?= $item->name ?></td>
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
                    <th>Name</th>
                    <th>Create</th>
                </tr>
                </thead>
                <tbody>
                <?php  foreach ($outcome as $item){?>
                    <tr>
                        <td><?= $item->name ?></td>
                        <td><?= Yii::$app->formatter->asDate($item->createdAt) ?></td>
                    </tr>
                    </tbody>
                <?php } ?>
            </table>
        </div>
    </div>

    <button type="button" class="btn btn-success" data-toggle="modal" data-target="#myModal">Add category</button>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add income category</h4>
                </div>
                <?php $form = ActiveForm::begin(['id' => 'add-category']); ?>
                <div class="modal-body">
                    <?= $form->field($model, 'name') ?>

                    <div class="form-group">
                        <label>Currency</label>
                        <?= Html::activeDropDownList($model, 'type', ArrayHelper::index($categories, function($e){return $e;}), ['class' => 'form-control']) ?>
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
</div>
