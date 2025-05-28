<?php
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\MaskedInput;
use yii\web\JsExpression;
use kartik\widgets\Select2;

//$url = \yii\helpers\Url::to(['/site/hosplist']);

$this->title = Yii::t('user', 'Sign in');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row justify-content-center h-100 align-items-center">
    <div class="col-md-6">
        <div class="card bg-primary">
            <div class="card-header text-white h4">ลงทะเบียนใช้งาน</div>
            <div class="card-body bg-white">
                <div class="">
                    <div class="row no-gutters">
                        <div class="col-xl-12">
                            <?php
                            $form = ActiveForm::begin([
                                        'id' => 'connect-account-form',
                            ]);
                            ?>
                            <?= $form->field($model, 'email') ?>
                            <?= $form->field($model, 'username')->label('ชื่อผู้ใช้งาน (ภาษาอังกฤษ) ห้ามเว้นวรรค') ?>
                            <?php
                            /*
                              $form->field($model, 'cid')->widget(MaskedInput::className(), [
                              'mask' => '9-9999-99999-99-9',
                              'clientOptions' => [
                              'removeMaskOnSubmit' => true,
                              ]
                              ])
                             *
                             */
                            ?>
                            <?php #= $form->field($model, 'name') ?>
                            <?php #= $form->field($model, 'lname') ?>
                            <?php
                            /*
                              echo $form->field($model, 'hospcode')->widget(Select2::classname(), [
                              'initValueText' => (isset($model->hospcode) ? $model->hospcode : '00000'), // set the initial display text
                              'options' => ['placeholder' => 'เลือกสถานบริการ...'],
                              'pluginOptions' => [
                              'allowClear' => true,
                              'minimumInputLength' => 0,
                              'language' => [
                              'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                              ],
                              'ajax' => [
                              'url' => $url,
                              'dataType' => 'json',
                              'data' => new JsExpression('function(params) { return {q:params.term}; }')
                              ],
                              //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                              'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                              'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
                              ],
                              ]);
                             *
                             */
                            ?>
                            <?= Html::submitButton('ลงทะเบียนใช้งาน', ['class' => 'btn btn-success btn-lg btn-block']) ?>
                            <?php ActiveForm::end(); ?>

                        </div>
                        <p class="text-center">
                            <?=
                            Html::a(
                                    Yii::t(
                                            'user', 'If you already registered, sign in and connect this account on settings page'
                                    ), ['/user/settings/networks']
                            )
                            ?>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

