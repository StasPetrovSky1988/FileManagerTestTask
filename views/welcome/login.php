<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

$this->title = 'Login';

?>
<div class="justify-content-center d-flex">
    <div class="site-login"  style="max-width: 300px;">
        <h1 class="text-center"><?= Html::encode($this->title) ?></h1>

        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => "{label}\n{input}\n{error}",
                'labelOptions' => ['class' => 'col-lg-1 col-form-label mr-lg-3'],
                'inputOptions' => ['class' => 'col-lg-3 form-control'],
                'errorOptions' => ['class' => 'col-lg-7 invalid-feedback'],
            ],
        ]); ?>

        <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'value' => Yii::$app->params['adminEmail']]) ?>
        <?= $form->field($model, 'password', )->passwordInput(['value' => 'qwerty']) ?>
        <?= $form->field($model, 'rememberMe')->checkbox([
            'template' => "<div class=\"custom-control  custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
        ]) ?>


        <div class="form-group">
            <div class="d-flex">
                <?= Html::submitButton('Login', ['class' => 'btn btn-primary btn-lg', 'name' => 'login-button', 'style' => 'width: 100%;']) ?>
            </div>
        </div>

        <?php ActiveForm::end(); ?>

        <br>
        <br>
        <div class="text-center">
            <a  href="/forgot" class="mb-3">Forgot</a> |
            <a  href="/register" class="mb-3">Register</a>
        </div>
    </div>
</div>
