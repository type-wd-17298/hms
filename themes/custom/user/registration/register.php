<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
//use app\models\Chospital;
//use kartik\widgets\Select2;
//use yii\helpers\ArrayHelper;
#use yii\captcha\Captcha;
#use yii\web\JsExpression;
use yii\widgets\MaskedInput;

//$url = \yii\helpers\Url::to(['/site/hosplist']);

$this->title = Yii::t('user', 'Sign up');
$this->params['breadcrumbs'][] = $this->title;
?>
<br>

<div class="row justify-content-center h-100 align-items-center">
    <div class="col-md-12">
        <div class="card bg-primary">
            <div class="card-header text-white h4">ลงทะเบียนใช้งาน</div>
            <div class="card-body bg-white">
                <div class="authincation-content">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <div class="auth-form">
                                <?php
                                $form = ActiveForm::begin([
                                            'id' => 'registration-form',
                                            'enableAjaxValidation' => true,
                                            'enableClientValidation' => true,
                                            'validateOnBlur' => false,
                                ]);
                                ?>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'username', ['inputOptions' => ['class' => 'form-control', 'placeholder' => '']]) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'password')->passwordInput() ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'passwordconfirm')->passwordInput() ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'email') ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'emailconfirm') ?>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'name') ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'lname') ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?=
                                        $form->field($model, 'cid')->widget(MaskedInput::className(), [
                                            'mask' => '9-9999-99999-99-9',
                                            'clientOptions' => [
                                                'removeMaskOnSubmit' => true,
                                            ]
                                        ])
                                        ?>
                                    </div>
                                </div>
                                <hr>
                                <?= Html::submitButton(Yii::t('user', 'Sign up'), ['class' => 'btn btn-primary btn-block']) ?>
                                <?php ActiveForm::end(); ?>


                                <div class="new-account mt-3">
                                    <?= Html::a(Yii::t('user', 'Already registered? Sign in!'), ['/user/security/login']) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>