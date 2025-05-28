<?php

use yii\helpers\Html;
use kartik\date\DatePicker;
use kartik\form\ActiveForm;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

$js = <<<JS
    setReceiver('{$model->travelby}');
    setWithdraw();
    $('#paperlessapproval-withdraw').change(function(){
        setWithdraw();
    });
    $('#paperlessapproval-travelby').change(function(){
        //var chk = $('#paperlessapproval-travelby').val();
        var chk = $("input[name='PaperlessApproval[travelby]']:checked").val();
        setReceiver(chk);
    });
    function setWithdraw(){
        var chk = $("input[name='PaperlessApproval[withdraw]']:checked").val();
        if(chk == 4){
             $('#withdraw_from').removeClass('d-none');
         }else{
             $('#withdraw_from').addClass('d-none');
         }
    }

    function setReceiver(chk){
         $('#vehicle_personal').addClass('d-none');
         $('#driver').addClass('d-none');
        if(chk>0){
            if(chk == 1){
                $('#driver').removeClass('d-none');
            }
            if(chk == 2){
                $('#vehicle_personal').removeClass('d-none');
            }
        }
    }

JS;
$this->registerJs($js, $this::POS_READY);
?>
<?php if (Yii::$app->session->hasFlash('alert')): ?>
    <?php
    $op = ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'options');
    ?>
    <div class="alert <?= $op['class'] ?> alert-dismissible fade show  mt-2" role="alert">
        <p class="mb-0">
            <?= ArrayHelper::getValue(Yii::$app->session->getFlash('alert'), 'body') ?>
        </p>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true"><i class="feather icon-x-circle"></i></span>
        </button>
    </div>

<?php endif; ?>
<div class="card">
    <div class="card-body">
        <?php
        $form = ActiveForm::begin([
                    'enableClientValidation' => false,
                    // 'type' => ActiveForm::TYPE_HORIZONTAL,
                    //'formConfig' => ['labelSpan' => 3, 'deviceSize' => ActiveForm::SIZE_LARGE],
                    'fieldConfig' => [
                        'template' => "<div class='font-weight-bold'>{label}</div>\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    ],
        ]);
        print_r($form->errorSummary($model));
        ?>

        <?php
        echo $form->field($model, 'paperless_ref_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(\app\modules\office\models\PaperlessOfficial::find()->where(['paperless_id' => $model->paperless_ref_id])->orderBy(['paperless_official_date' => SORT_DESC])->all(), 'paperless_id', 'fullpaper'),
            'id' => 'deps',
            'options' => [
                'placeholder' => '--เลือก--',
                'multiple' => false,
            //'class' => 'form-control form-control-lg',
            ],
            'theme' => Select2::THEME_KRAJEE_BS5,
            'pluginOptions' => [
                //'dropdownParent' => '#modalContents',
                // 'allowClear' => true,
                'minimumInputLength' => 1,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['/office/approval/paperlesslist']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
            ],
        ]);
        ?>


        <?PHP
        /*
          echo $form->field($model, 'paperless_ref_id')->dropDownList(
          ArrayHelper::map(\app\modules\office\models\PaperlessOfficial::find()
          ->where(['paperless_official_type' => 'BRN'])
          ->orderBy(['paperless_id' => SORT_ASC])
          ->limit(10)
          ->all(), 'paperless_id', 'fullpaper'),
          [
          //'disabled' => $model->isNewRecord ? false : true,
          'prompt' => '--หนังสือต้นเรื่อง/หนังสืออ้างอิง--',
          'class' => 'form-control form-control-lg'
          ]
          );
         *
         */
        ?>
        <?= $form->field($model, 'topic')->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'place')->textInput(['maxlength' => true]) ?>
        <div class="row">
            <div class="col-6">
                <?=
                $form->field($model, 'startdate')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่', 'class1' => 'form-control form-control-lg'],
                    'language' => 'th-TH',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayBtn' => true,
                        'todayHighlight' => true,
                    ]
                ]);
                ?>
            </div>
            <div class="col-6">
                <?=
                $form->field($model, 'enddate')->widget(DatePicker::classname(), [
                    'options' => ['placeholder' => 'วันที่', 'class1' => 'form-control form-control-lg'],
                    'language' => 'th-TH',
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd',
                        'todayBtn' => true,
                        'todayHighlight' => true,
                    ]
                ]);
                ?>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <?= $form->field($model, 'organized')->textInput() ?>
            </div>
            <div class="col-6">
                <?= $form->field($model, 'approval_day')->textInput(['type' => 'number']) ?>
            </div>
        </div>
        <?php
        echo $form->field($model, 'employee_own_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(\app\modules\hr\models\Employee::find()->where(['employee_status' => 1])->orderBy(['employee_id' => SORT_ASC])->all(), 'employee_id', 'employee_fullname'),
            'disabled' => $model->isNewRecord ? false : true,
            'options' => [
                'placeholder' => '--เลือก--',
                // 'multiple' => false,
                'class' => 'form-control form-control-lg',
            ],
            'theme' => Select2::THEME_KRAJEE_BS5,
            'pluginOptions' => [
                //'dropdownParent' => '#modalForm',
                // 'allowClear' => true,
                'minimumInputLength' => 0,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['/office/paperless/emplist', 'mode' => 'D']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
            ],
        ]);
        ?>
        <?php
        echo $form->field($model, 'employee_id')->widget(Select2::classname(), [
            //'name' => 'paperless_view_emps',
            'data' => ArrayHelper::map(\app\modules\hr\models\Employee::find()->where(['employee_status' => 1])->orderBy(['employee_id' => SORT_ASC])->all(), 'employee_id', 'employee_fullname'),
            'id' => 'employee_id',
            'options' => [
                'placeholder' => 'เลือกมอบให้(เจ้าหน้าที่)...',
                'multiple' => true,
            //'class' => 'form-control form-control-lg',
            ],
            'theme' => Select2::THEME_MATERIAL,
//                'pluginEvents' => [
//                    "select2:select" => "function(d) { offerText(d.params.data); }",
//                ],
            'pluginOptions' => [
                'maintainOrder' => true,
                // 'dropdownParent' => '#modalForm',
                'allowClear' => false,
                'minimumInputLength' => 0,
                'language' => [
                    'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                ],
                'ajax' => [
                    'url' => Url::to(['/office/paperless/emplist']),
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }')
                ],
                //'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(hos) { return hos.text; }'),
                'templateSelection' => new JsExpression('function (hos) { return hos.text; }'),
            ],
        ]);
        ?>
        <div class="row">
            <div class="col-6">
                <?PHP
                echo $form->field($model, 'approval_type_id')->radioList(
                        ArrayHelper::map(\app\modules\office\models\PaperlessApprovalTravel::find()
                                        //->where(['paperless_official_type' => 'BRN'])
                                        ->orderBy(['approval_type_id' => SORT_ASC])
                                        ->limit(10)
                                        ->all(), 'approval_type_id', 'approval_type_name'),
                        [
                            //'disabled' => $model->isNewRecord ? false : true,
                            // 'prompt' => '--ประเภทการไป--',
                            //'class' => 'form-control form-control-lg'
                            'class' => 'form-check'
                        ]
                );
                ?>
            </div>
            <div class="col-6">
                <?PHP
                echo $form->field($model, 'develop_id')->checkboxList(
                        ArrayHelper::map(\app\modules\office\models\PaperlessApprovalDevelop::find()
                                        //->where(['paperless_official_type' => 'BRN'])
                                        ->orderBy(['develop_id' => SORT_ASC])
                                        ->limit(10)
                                        ->all(), 'develop_id', 'develop_name'),
                        [
                            //'disabled' => $model->isNewRecord ? false : true,
                            // 'prompt' => '--สมรรถนะที่ได้รับ--',
                            //'class' => 'form-control form-control-lg'
                            'custom' => true,
                            'inline' => false,
                            'class' => 'form-check'
                        ]
                );
                ?>
            </div>
        </div>



        <div>
            <?PHP
            echo $form->field($model, 'travelby')->radioList(
                    ArrayHelper::map(\app\modules\office\models\PaperlessApprovalType::find()
                                    //->where(['paperless_official_type' => 'BRN'])
                                    ->orderBy(['vehicle_id' => SORT_ASC])
                                    ->limit(10)
                                    ->all(), 'vehicle_id', 'vehicle_type'),
                    [
                        //'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--โดยยานพาหนะ--',
                        //'class' => 'form-control form-control-lg'
                        'custom' => true, 'inline' => true, 'class' => 'form-check form-check-inline'
                    ]
            );
            ?>
        </div>
        <div id="driver">
            <?PHP echo $form->field($model, 'driver')->radioList(['Y' => 'พร้อมพนักงานขับรถ', 'N' => 'ไม่ต้องการ'], ['custom' => true, 'inline' => true, 'class' => 'form-check form-check-inline']) ?>

        </div>
        <div id="vehicle_personal">
            <?= $form->field($model, 'vehicle_personal')->textInput(['maxlength' => true]) ?>
        </div>
        <?PHP
        echo $form->field($model, 'withdraw')->radioList(
                ArrayHelper::map(\app\modules\office\models\PaperlessApprovalBudget::find()
                                //->where(['paperless_official_type' => 'BRN'])
                                ->orderBy(['budget_id' => SORT_ASC])
                                ->limit(10)
                                ->all(), 'budget_id', 'budget_type'),
                [
                    //'disabled' => $model->isNewRecord ? false : true,
                    'prompt' => '--ขอเบิก--',
                    'custom' => true, 'inline' => true, 'class' => 'form-check form-check-inline'
                ]
        );
        ?>
        <?PHP //$form->field($model, 'withdraw')->radioList(['N' => 'ไม่เบิก', 'Y' => 'เบิก'], ['custom' => true, 'inline' => true, 'class' => 'form-check form-check-inline']) ?>

        <div id="withdraw_from">
            <?= $form->field($model, 'withdraw_from')->textInput(['maxlength' => true]) ?>
        </div>
        <hr>
        <div class="form-group">
            <div class="row justify-content-between mt-3 mb-5">
                <div class="col-6">
                    <?= Html::a('<i class="la la-angle-left la-lg"></i> กลับหน้าหลัก', ['index'], ['class' => 'btn btn-light btn-lg btn-dark', 'data-bs-dismiss' => 'modal1', 'data-pjax' => 0]) ?>
                </div>
                <div class="col-6 text-right">
                    <?= Html::submitButton('<i class="fas fa-plus fa-lg"></i> ดำเนินการต่อ >>', ['class' => 'btn btn-primary btn-lg']) ?>
                </div>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>