<?php

use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use app\components\Ccomponent;

#use yii\widgets\Pjax;
#use yii\helpers\ArrayHelper;
#use kartik\icons\Icon;
$url = Url::to(['statement']);

$js1 = <<<JS

    $(".btnLink").click(function(){
        $('#epayslipModal').modal('show');
        $.get("{$url}",{pid:$(this).data("id"),yymm:$(this).data("yymm")}, function(data) {
           $("#epayslipContent").html(data);
        });
    });
    /*
    $(".btnPrint").click(function(){
        $('#epayslipModal').modal('show');
        $.get("{$url}",{pid:$(this).data("id"),yymm:$(this).data("yymm"),print:1}, function(data) {
           $("#epayslipContent").html(data);
        });
    });
     */
    $('#epayslipModal').on('show.bs.modal', function (e) {

    });

JS;
$this->registerJs($js1, $this::POS_READY);
?>

<div class="card">
    <div class="card-header">
        <div class="card-title">รายการเงินเดือน</div>
    </div>
    <div class="card-content">
        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            /*
              'toolbar' => [
              [
              'content' =>
              Html::a('<i class = "glyphicon glyphicon-plus"></i> แนบเอกสารใหม่', ['create',], [
              'class' => 'btn btn-success',
              ]) . ' ' .
              Html::a('กลับหน้าหลัก', ['default/usekpi', 'byear' => ''], [
              'class' => 'btn btn-outline-secondary',
              ]),
              'options' => ['class' => 'btn-group mr-2']
              ],
              '{toggleData}',
              ],
              'panel' => [
              'type' => GridView::TYPE_PRIMARY,
              'heading' => 'รายการเงินเดือน<small>ssss</small>',
              ],
             *
             */
            'layout' => '{items}',
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            // set export properties
            'export' => FALSE,
            'persistResize' => FALSE,
            'bordered' => FALSE,
            'striped' => true,
            'condensed' => true,
            'responsive' => true,
            'responsiveWrap' => false,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'ปีที่จ่าย',
                    'attribute' => 'salaryMonth',
                    'format' => 'raw',
                    'hAlign' => 'center',
                    'value' => function ($data) {
                        return substr($data['salaryMonth'], 0, 4);
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'เดือนที่จ่าย',
                    'attribute' => 'salaryMonth',
                    'format' => 'raw',
                    'hAlign' => 'center',
                    'value' => function ($data) {
                        return Ccomponent::getThaiMonth(substr($data['salaryMonth'], -2, 2), 'L');
                    }
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'รวมรับทั้งเดือน',
                    'attribute' => 'get_salary',
                    'format' => ['decimal', 2],
                    'vAlign' => 'middle',
                    'hAlign' => 'right',
                //'visible' => false,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'รวมจ่ายทั้งเดือน',
                    'attribute' => 'put_salary',
                    'format' => ['decimal', 2],
                    'vAlign' => 'middle',
                    'hAlign' => 'right',
                    'value' => function ($data) {
                        return abs($data['put_salary']);
                    }
                //'visible' => false,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'รับสุทธิ',
                    'attribute' => 'salary',
                    'format' => ['decimal', 2],
                    'vAlign' => 'middle',
                    'hAlign' => 'right',
                //'visible' => false,
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'label' => 'แสดงรายละเอียด',
                    'attribute' => 'salaryMonth',
                    'format' => 'raw',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'noWrap' => TRUE,
                    'value' => function ($data) {

                        return Html::button('รายละเอียด <span class="btn-icon-end"><i class="fa-solid fa-file-lines"></i></span>', [
                            'class' => 'btn btn-primary btnLink btn-xs',
                            'data-id' => $data['pid'],
                            'data-yymm' => $data['salaryMonth'],
                        ]);
                    }
                ],
            ],
        ]);
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="epayslipModal"  aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title">ใบแจ้งรายการเงินเดือน</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body1 m-3">
                <div id="epayslipContent"></div>
            </div>
            <div class="modal-footer d-none">
                <button type="button" class="btn btn-danger light btn-lg" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>
