<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'New User';

?>
<div class="justify-content-center d-flex">
    <div class="site-login"  style="max-width: 300px;">
        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>
        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($model, 'email') ?>
        <?= $form->field($model, 'password') ?>

        <div class="form-group">
            <div class="d-flex">
                <?= Html::submitButton('Register', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button', 'style' => 'width: 100%;']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <br>
        <div class="text-center">
            <a  href="/forgot" class="mb-3">Forgot</a> |
            <a  href="/" class="mb-3">Login</a>
        </div>

    </div>
</div>
