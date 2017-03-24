<?php


use yii\helpers\Html;
use frontend\assets\CropperAsset;
use frontend\assets\SiteSettingsAsset;


CropperAsset::register($this);
SiteSettingsAsset::register($this);

$this->title = 'Account';
?>
<div class="settings-page">
    <h1>Settings page</h1>
    <div class="settings-wrap">
        <div class="info-form">

        </div>
        <div class="avatar-form">
            <div class="preview">
                <span>
                    <img src="/img/user.png">
                </span>
            </div>
            <div class="crop-wrap"><img src="#" id="crop"></div>

            <button type="button" id="upload" class="btn btn-primary"><?= Yii::t('app', 'Save') ?></button>

            <label for="avatarInput" class="btn btn-success"><?= Yii::t('app', 'Load image') ?></label>
            <input type="file" name="file" id="avatarInput" class="form-control">

        </div>
    </div>
</div>
