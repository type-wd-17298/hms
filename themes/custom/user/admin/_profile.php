<?php
/*
 * This file is part of the Dektrium project
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use app\modules\survay\models\Cdepartment;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View 					$this
 * @var dektrium\user\models\User 		$user
 * @var dektrium\user\models\Profile 	$profile
 */
?>

<?php $this->beginContent('@dektrium/user/views/admin/update.php', ['user' => $user]) ?>
<?php
$url = \yii\helpers\Url::to(['/site/deplist']); //Chospital::findOne($model->depcode)
$depcodeDesc = empty($profile->depcode) ? '1111111111' : $profile->depcode . ' ' . Cdepartment::findOne(['department_code' => $profile->depcode])->department_name;
?>

<?php
$form = ActiveForm::begin([
            'layout' => 'horizontal',
            'enableAjaxValidation' => true,
            'enableClientValidation' => false,
            'fieldConfig' => [
                'horizontalCssClasses' => [
                    'wrapper' => 'col-sm-9',
                ],
            ],
        ]);
?>

<?=
$form->field($profile, 'cid')->widget(MaskedInput::className(), [
    'mask' => '9-9999-99999-99-9',
    'clientOptions' => [
        'removeMaskOnSubmit' => true,
    ]
])
?>

<?= $form->field($profile, 'name') ?>
<?= $form->field($profile, 'lname') ?>
<?php
/*
  echo $form->field($profile, 'depcode')->widget(Select2::classname(), [
  'initValueText' => $depcodeDesc, // set the initial display text
  'options' => ['placeholder' => 'เลือกหน่วยงาน...'],
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
<?= $form->field($profile, 'public_email') ?>
<?= $form->field($profile, 'website') ?>
<?= $form->field($profile, 'location') ?>
<?= $form->field($profile, 'gravatar_email') ?>
<?= $form->field($profile, 'bio')->textarea() ?>

<div class="form-group">
    <div class="col-lg-offset-3 col-lg-9">
        <?= Html::submitButton(Yii::t('user', 'Update'), ['class' => 'btn btn-block btn-success']) ?>
    </div>
</div>

<?php ActiveForm::end(); ?>

<?php $this->endContent() ?>
