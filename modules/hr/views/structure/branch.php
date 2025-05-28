<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

$js = <<<JS
       $.pjax.reload({container: '#gridView99', async: false});
JS;
Pjax::begin(['id' => 'frm99', 'timeout' => false, 'enablePushState' => false]);
$this->registerJs($js, $this::POS_READY);
?>
<div style='overflow-x:auto;overflow-y:hidden;width:auto;'>
    <div style='min-width:1000px;min-height: 300px;'>
        <?= Html::tag('div', $tree['html'], ['class' => 'tree']); ?>
    </div>
</div>
<hr>
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title font-weight-bold">เพิ่มหน่วยงาน</h5>
            </div>
            <div class="card-body">
                <?php
                $form = ActiveForm::begin([
                            //'action' => (isset($_GET['id']) ? ['update', 'id' => $_GET['id']] : ['create']),
                            'options' => [
                                'data-pjax' => true,
                            ],
                                //'enableClientValidation' => true,
                                //'enableAjaxValidation' => true,
                ]);
                ?>
                <?= $form->field($model, 'employee_dep_label')->textInput(['class' => 'form-control form-control-lg']) ?>
                <?php
                if (is_array($tree['data'])) {
                    $data = $tree['data'];
                } else {
                    $data = [$tree['data']];
                }
                if (isset($dataArray[0]['employee_dep_parent']) && !empty($dataArray[0]['employee_dep_parent']))
                    $data = array_merge($data, [$dataArray[0]['employee_dep_parent']]);

                echo $form->field($model, 'employee_dep_parent')->dropDownList(
                        ArrayHelper::map(\app\modules\hr\models\EmployeeDep::find()
                                        //->where(['IN', 'employee_dep_id', @$tree['data']])
                                        ->where(['OR',
                                            ['IN', 'employee_dep_id', $data],
                                                //['employee_dep_id' => $model->employee_dep_parent]
                                        ])
                                        ->orderBy(['employee_dep_id' => SORT_ASC])
                                        ->all(), 'employee_dep_id', 'employee_dep_label'),
                        [
                            'disabled' => $model->isNewRecord ? false : true,
                            'prompt' => '--เลือกหมวดหมู่หลัก--',
                            'class' => 'form-control form-control-lg'
                        ]
                );
                ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fas fa-save fa-lg"></i> บันทึกรายการ', ['class' => 'btn btn-primary']) ?>
                    <?= Html::button('ยกเลิก', ['class' => 'btn btn-outline-danger']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-8 small">
        <?PHP
        echo GridView::widget([
            'id' => 'gview01',
            'dataProvider' => @$dataProvider,
            //'tableOptions' => ['class' => 'table verticle-middle table-responsive-md'],
            'panel' => [
                'heading' => '',
                'type' => 'primary',
                'before' => Html::tag('h4', 'รายชื่อหน่วยงาน', ['class' => 'font-weight-bold']),
                'footer' => false,
            ],
            'panelBeforeTemplate' => '{before}',
            'panelTemplate' => '<div class="">
  {panelBefore}
  {items}
  {panelAfter}
  {panelFooter}
  <div class="text-center m-2">{summary}</div>
  <div class="text-center m-2">{pager}</div>
  <div class="clearfix"></div>
  </div>',
            'responsiveWrap' => FALSE,
            'striped' => FALSE,
            'hover' => TRUE,
            'bordered' => FALSE,
            'condensed' => FALSE,
            'export' => FALSE,
            //'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
            'exportContainer' => ['class' => ''],
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'กลุ่มงาน/ชื่อหน่วยงาน',
                    'attribute' => 'employee_dep_label',
                    'format' => 'raw',
                    'visible' => 1,
                //'group' => true,
                ],
                [
                    'visible' => 1,
                    'label' => 'ดำเนินการ',
                    //'attribute' => 'docs_filename',
                    //'width' => '10%',
                    'format' => 'raw',
                    'vAlign' => 'middle',
                    'noWrap' => TRUE,
                    'hAlign' => 'right',
                    'value' => function ($model) {
                        $html = '<div class="btn-group btn-group-toggle btn-group-xs">
                  <label class="btn btn-primary btn-xs"><i class="fas fa-file-alt fa-lg"></i>
                  ' . Html::a('แก้ไข', ['', 'id' => @$_GET['id'], 'idu' => $model['employee_dep_id']], ['class' => 'text-white text-decoration-none']) . '
                  </label>
                  <label class="btn btn-dark btn-xs"><i class="fas fa-remove fa-lg"></i>
                  ' . Html::a('ลบ', ['delete', 'id' => $model['employee_dep_id']], [
                                    'class' => 'text-white text-decoration-none',
                                    'data' => [
                                        'pjax' => 1,
                                        'confirm' => 'คุณต้องการลบข้อมูลนี้หรือไม่?',
                                        'method' => 'post',
                                    ],
                                ]) . '
                  </label>
                  </div>';
                        return $html; //Html::a('<i class="fas fa-file-alt fa-lg"></i> เอกสาร', '//', ['class' => 'btn btn-warning btn-sm btn-block']);
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>

<?php Pjax::end() ?>