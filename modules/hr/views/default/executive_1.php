<?php

use yii\helpers\Url;
//use yii\helpers\Html;
use kartik\grid\GridView;

//use yii\widgets\Pjax;
//use kartik\widgets\DatePicker;
$url = Url::to(['employee/addexecutive']);
$js = <<<JS
$("[data-toggle=tooltip").tooltip();
$(".btnProcess").click(function(event){
       event.preventDefault();
        var keys = $("#w0").yiiGridView("getSelectedRows");
         if(keys.length>0){
            var cbIDs = new Array();
            $(".ids-checkbox:checked").each(function(i) {
               cbIDs.push($(this).val());
            });

                $.post("{$url}",{id:'{$emp->employee_id}',ids:cbIDs},  function(data) {
                    Swal.fire({
                     icon: 'success',
                     title: 'เพิ่มรายการเรียบร้อยแล้วค่ะ \\n สำเร็จ '+data.success+' ไม่สำเร็จ '+data.error,
                     showConfirmButton: false,
                     timer: 2000
                   });
                });
         }
});

JS;
$this->registerJs($js, $this::POS_READY);
?>

<!-- Nav tabs -->
<div class="default-tab">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> ข้อมูลทั่วไป</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#profile"><i class="la la-television me-2"></i> ข้อมูลตำแหน่งบริหาร</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#paper"><i class="la la-comments me-2"></i> เอกสาร</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="pt-4">
                <?=
                $this->render('_form_executive', [
                    'model' => $model,
                ])
                ?>
                <?PHP
                echo GridView::widget([
                    'dataProvider' => $dataProvider,
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
                    'striped' => TRUE,
                    'hover' => TRUE,
                    'bordered' => FALSE,
                    'condensed' => TRUE,
                    'export' => FALSE,
                    'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                    'panel' => [
                        'heading' => '',
                        'type' => 'primary',
                        'before' => '<form>
                  <div class="input-group mb-3 input-primary">
                     <input type="text" class="form-control" placeholder="ชื่อรายการ">
                     <button type="button" class="btn btn-primary"><i class="fa fa-search"></i> แสดงข้อมูล</button>
                     <button type="button" class="btn btn-dark btnProcess"><i class="fa fa-arrow-right"></i> เพิ่มรายการ/แก้ไขรายการ</button>
                 </div>
</form>
<div class="h2">' . $emp->employee_fullname . '</div>

'
                    ,
                    ],
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'class' => '\kartik\grid\CheckboxColumn',
                            'name' => 'cbIDs',
                            /*
                              'content' => function ($model) {
                              return '<div class="form-check custom-checkbox mb-3 checkbox-success">
                              </div>';
                              },
                             *
                             */
                            'checkboxOptions' => function ($model) use ($list) {
                                return [
                            'value' => $model['employee_executive_id'],
                            'class' => 'ids-checkbox',
                            'checked' => in_array($model['employee_executive_id'], $list) ? true : false
                                ];

                                //return ['checked' => $model['val'] ? true : false];
                            },
                        //'GridView' => TYPE_DANGER
                        ],
                        /*
                          [
                          'headerOptions' => ['class' => 'font-weight-bold'],
                          'label' => 'เลือกรายการ',
                          'radioOptions' => function ($model, $key, $index, $column) {
                          return ['value' => $model->name  ];
                          }
                          ],
                         *
                         */
                        [
                            'headerOptions' => ['class' => 'font-weight-bold'],
                            'label' => 'ชื่อตำแหน่งบริหาร',
                            'attribute' => 'employee_executive_name',
                            'format' => 'raw',
                            'noWrap' => TRUE,
                            'visible' => 1,
                        ],
                    ],
                ]);
                ?>
            </div>
        </div>
        <div class="tab-pane fade" id="paper">

        </div>
    </div>
</div>

