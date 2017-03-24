<?php


use yii\helpers\Html;
use frontend\assets\CropperAsset;
use frontend\assets\SiteSettingsAsset;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;


CropperAsset::register($this);
SiteSettingsAsset::register($this);

$this->title = 'Account';
$this->params['user'] = $user;
?>
<div class="settings-page">
    <h1><?= Yii::t('app', 'Settings page') ?></h1>
    <div class="settings-wrap">
        <div class="info-form">
            <?php $form = ActiveForm::begin(['id' => 'user']); ?>

            <?= $form->field($user, 'username')->textInput() ?>

            <?= $form->field($user, 'firstName')->textInput() ?>

            <?= $form->field($user, 'lastName')->textInput() ?>

            <?= $form->field($user, 'email')->textInput() ?>

            <?= $form->field($user, 'phone')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
        <div class="avatar-form">
            <div class="preview">
                <img src="<?= Html::encode($user->avatarUrl)?>">
            </div>

            <div class="avatar-controls">
                <button type="button" id="upload" class="btn btn-primary"><?= Yii::t('app', 'Save') ?></button>
                <label for="avatarInput" class="btn btn-success"><?= Yii::t('app', 'Load image') ?></label>
                <input type="file" name="file" id="avatarInput" class="form-control">
            </div>
            <div class="crop-wrap"><img src="#" id="crop"></div>
        </div>
    </div>
</div>
