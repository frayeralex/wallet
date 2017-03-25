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
        <section class="info-form">
            <h2><?= Yii::t('app', 'Change user info') ?></h2>
            <?php $form = ActiveForm::begin(['id' => 'user']); ?>

            <?= $form->field($user, 'username')->textInput() ?>

            <?= $form->field($user, 'firstName')->textInput() ?>

            <?= $form->field($user, 'lastName')->textInput() ?>

            <?= $form->field($user, 'email')->textInput() ?>

            <?= $form->field($user, 'phone')->textInput() ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-primary']) ?>
            </div>
        </section>
        <section class="avatar-form">
            <h2><?= Yii::t('app', 'Change avatar') ?></h2>
            <div class="preview">
                <img src="<?= Html::encode($user->avatarUrl)?>">
            </div>

            <div class="avatar-controls">
                <button type="button" id="upload" class="btn btn-primary"><?= Yii::t('app', 'Save') ?></button>
                <label for="avatarInput" class="btn btn-success"><?= Yii::t('app', 'Load image') ?></label>
                <button type="button" id="removeAvatar" class="btn btn-danger"><?= Yii::t('app', 'Remove avatar') ?></button>
                <input type="file" name="file" id="avatarInput" class="form-control">
            </div>
            <div class="crop-wrap"><img src="#" id="crop"></div>
        </section>
        <section class="info-form">
            <h2><?= Yii::t('app', 'Change password') ?></h2>
            <?php ActiveForm::end(); ?>

            <?php $form = ActiveForm::begin(['id' => 'form-change-password']); ?>

            <?= $form->field($password, 'password')->passwordInput() ?>

            <?= $form->field($password, 'newPassword')->passwordInput() ?>

            <?= $form->field($password, 'repeatPassword')->passwordInput() ?>


            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Change password'), ['class' => 'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </section>
        <section class="info-form">
            <h2><?= Yii::t('app', 'Find declarations') ?></h2>
            <div class="global-search" id="global-search">
                <div class="form-group">
                    <input type="search" class="form-control">
                    <div class="loader"></div>
                </div>
                <ul class="search-results" id="search-results">
                </ul>
            </div>
        </section>
    </div>
</div>
