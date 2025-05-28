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
use app\modules\survay\models\Cdepartment;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;

/**
 * @var yii\web\View $this
 * @var yii\widgets\ActiveForm $form
 * @var dektrium\user\models\Profile $profile
 */
$this->title = Yii::t('user', 'Profile settings');
$this->params['breadcrumbs'][] = $this->title;

$url = \yii\helpers\Url::to(['/site/deplist']); //Chospital::findOne($model->depcode)
//$depcodeDesc = empty($model->depcode) ? '' : $model->depcode . ' ' . Cdepartment::findOne(['department_code' => $model->depcode])->department_name;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>
<?php
if (strlen(Yii::$app->user->identity->profile->cid) <> 13 || strlen(Yii::$app->user->identity->profile->depcode) <> 3 || Yii::$app->user->identity->profile->name = '' || Yii::$app->user->identity->profile->lname == '') {
    ?>
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>*** กรุณากรอกข้อมูลให้ครบถ้วน ***</strong>
        <hr>
        <div>
            - หมายเลขประจำตัวประชาชน<br>
            - ชื่อ<br>
            - นามสกุล
            <!--            <br>- รหัสหน่วยงานของท่าน-->
        </div>
    </div>
<?php } ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white h4">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="card-body">
                <div class="">
                    <?php
                    $form = ActiveForm::begin([
                                'id' => 'profile-form',
                                //'options' => ['class' => 'form-horizontal'],
                                /*
                                  'fieldConfig' => [
                                  'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
                                  'labelOptions' => ['class' => 'col-lg-3 control-label'],
                                  ],
                                 */
                                'enableAjaxValidation' => true,
                                'enableClientValidation' => true,
                                    #'validateOnBlur' => false,
                    ]);
                    ?>
                    <?=
                    $form->field($model, 'cid')->widget(MaskedInput::className(), [
                        'mask' => '9-9999-99999-99-9',
                        'clientOptions' => [
                            'removeMaskOnSubmit' => true,
                        ],
                        'class' => 'form-control form-control-lg'
                    ])
                    ?>

                    <?= $form->field($model, 'name') ?>

                    <?= $form->field($model, 'lname') ?>
                    <?php
                    /*
                      echo $form->field($model, 'depcode')->widget(Select2::classname(), [
                      'initValueText' => $depcodeDesc, // set the initial display text
                      'options' => ['placeholder' => 'เลือกหน่วยงาน...'],
                      'disabled' => (strlen($model->depcode) == 3 ? 1 : 0),
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
                    <?php #= $form->field($model, 'public_email') ?>

                    <?php #= $form->field($model, 'website')  ?>

                    <?php #= $form->field($model, 'location')  ?>

                    <?php #= $form->field($model, 'gravatar_email')->hint(\yii\helpers\Html::a(Yii::t('user', 'Change your avatar at Gravatar.com'), 'http://gravatar.com')) ?>

                    <?php #= $form->field($model, 'bio')->textarea() ?>

                    <div class="form-group">
                        <div class="">
                            <?= \yii\helpers\Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-primary btn-lg']) ?><br>
                        </div>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
