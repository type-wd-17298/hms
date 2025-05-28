<?php

//use dektrium\user\widgets\Connect;
use yii\bootstrap4\Html;
//use kartik\form\ActiveForm;
use yii\bootstrap4\ActiveForm;

$this->title = Yii::$app->name;
//$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<div class="row justify-content-center h-100 align-items-center">
    <div class="col-xl-10 col-12  justify-content-center">
        <div class="card bg-authentication rounded-0 mb-0">
            <div class="row m-0">
                <div class="col-lg-6 d-lg-block d-none text-center align-self-center px-1 py-0">
                    <img src="<?= yii\helpers\Url::to('@web/img/login.jpg') ?>">
                </div>
                <div class="col-lg-6 col-12 p-0 bg-white">
                    <div class="rounded-0 mb-0 ">
                        <div class="">
                            <div class="h4 mt-3 text-center">
                                <b><?= Yii::$app->name ?></b>
                            </div>
                            <div class="m-4">
                                <?php if (1): ?>
                                    <?php
                                    $form = ActiveForm::begin([
                                                'id' => 'login-form',
                                                //'options' => ['class' => 'form-control form-control-lg'],
                                                'enableAjaxValidation' => true,
                                                'enableClientValidation' => false,
                                                'validateOnBlur' => false,
                                                'validateOnType' => false,
                                                'validateOnChange' => false,
                                            ])
                                    ?>
                                    <div class="mb-3">
                                        <?=
                                        $form->field(
                                                $model, 'login', ['inputOptions' => ['autofocus' => 'autofocus', 'class' => 'form-control form-control-lg', 'tabindex' => '1']]
                                        )->label(Html::tag('b', 'ชื่อผู้ใช้งาน'))
                                        ?>
                                    </div>
                                    <div class="mb-3">
                                        <?=
                                                $form
                                                ->field(
                                                        $model, 'password', ['inputOptions' => ['class' => 'form-control form-control-lg', 'tabindex' => '2']]
                                                )
                                                ->passwordInput()
                                                ->label(
                                                        Html::tag('b', 'รหัสผ่าน')
                                                        . ($module->enablePasswordRecovery ?
                                                                ' ' : '')
                                                )
                                        ?>
                                    </div>

                                    <?=
                                    Html::submitButton(
                                            Yii::t('user', 'Sign in'), ['class' => 'btn btn-outline-dark  btn-lg btn-block ', 'tabindex' => '3']
                                    )
                                    ?>
                                    <?php ActiveForm::end(); ?>
                                <?php endif ?>

                                <div class="mt-3">
                                    <p>
                                        Don't have an account?
                                        <?PHP // Html::a('ลงทะเบียนใช้งาน', ['/user/registration/register'], ['class' => 'text-primary',]) ?>
                                        <?= Html::a('ลงทะเบียนใช้งาน', ['/user/auth', 'authclient' => 'line'], ['class' => 'text--primary',]) ?>
                                    </p>

                                    <?php if ($module->enableRegistration): ?>
                                        <div class="row">
                                            <div class="col-6 d-none">
                                                <?PHP // Html::a('ลงทะเบียนใช้งาน<br>HMS', ['/user/auth'], ['class' => 'btn btn-lg btn-primary btn-block',]) ?>
                                                <?= Html::a('ลงทะเบียนใช้งาน<br>HMS', ['/user/auth', 'authclient' => 'line'], ['class' => 'btn btn-lg btn-primary btn-block',]) ?>

                                            </div>
                                            <!--
            <div class="col-6">
                                            <?= Html::a('ลืมรหัสผ่าน', ['/user/recovery/request'], ['class' => 'btn btn-lg btn-primary btn-block',]) ?>
            </div>
                                            -->
                                            <div class="col-12">
                                                <?=
                                                Html::a(Html::img('@web/img/line-logo.png', ['class' => ' float-right', 'width' => '40']) . 'คลิกเข้าใช้งานระบบผ่าน<br> <b>LINE APP</b>',
                                                        ['/user/auth', 'authclient' => 'line'], ['class' => 'btn btn-lg btn-primary btn-block',])
                                                ?>
                                            </div>
                                            <div class="col-12">
                                                <?=
                                                Html::a(Html::img('@web/img/logo-providerID.png', ['class' => ' float-right', 'width' => '100%']) . 'คลิกเข้าใช้งานผ่าน <br> <b>Provider ID</b>',
                                                        ['/user/auth', 'authclient' => 'providerid'], ['class' => 'btn btn-lg  btn-block',])
                                                ?>
                                            </div>
                                        </div>
                                        <div class="copyright text-center">
                                            <p>Copyright © โรงพยาบาลสมเด็จพระสังฆราช องค์ที่ 17
                                                <br><b>ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร</b>
                                            </p>
                                        </div>
                                    <?php endif ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>