<?php

use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\bootstrap4\Modal;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'เกณฑ์ราคากลางและคุณลักษณะพื้นฐานการจัดหาอุปกรณ์และระบบคอมพิวเตอร์ ฉบับเดือนมีนาคม 2566';
$this->params['breadcrumbs'][] = $this->title;

$homeUrl = Url::to(['/survey/default/index']);
$this->registerJs("
    document.getElementById('btnBack').addEventListener('click', function() {
        window.location.href = '{$homeUrl}';
    });
");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h2 class="mb-1"><?= Html::encode($this->title) ?></h2>
        <small class="text-muted">Last update : 13/08/2568</small>
    </div>
</div>
<hr>

<?php
$updateUrl = Url::to(['update-master']);
$deleteUrl = Url::to(['delete-master']);
$createUrl = Url::to(['create-master']);
?>

<?php
$js = <<<JS
$('#updateModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');

    $('#modalContent').html('กำลังโหลด...');
    $.get('{$updateUrl}', {id: id}, function(data){
        $('#modalContent').html(data);
    });
});

$('#createModal').on('show.bs.modal', function () {
    var url = '{$createUrl}'; 
    $('#createContent').html('กำลังโหลด...');

    $.get(url, function(data){
        $('#createContent').html(data);

        $('#create-form').off('submit').on('submit', function(e){
            console.log("mtest data"); 
            var form = $(this);

            $.ajax({
                url: form.attr('action'),
                type: 'post',
                data: form.serialize(),
                success: function(response){
                    if(response.success){
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ',
                            text: response.message,
                            timer: 2000,
                            showConfirmButton: false
                        });
                        $('#createModal').modal('hide');
                        $.pjax.reload({container:'#grid-pjax'});
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'ผิดพลาด',
                            text: response.message || 'เกิดข้อผิดพลาด'
                        });
                    }
                },
                error: function(){
                    Swal.fire({
                        icon: 'error',
                        title: 'ผิดพลาด',
                        text: 'ไม่สามารถบันทึกข้อมูลได้'
                    });
                }
            });

            return false; 
        });
    }).fail(function(xhr){
        console.log("Ajax GET fail", xhr.status, xhr.responseText);
        $('#createContent').html('โหลดฟอร์มไม่สำเร็จ');
    });
});


$(document).on('click', '.btn-delete-master', function(e) {
    e.preventDefault();
    var id = $(this).data('id');

    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: "คุณต้องการลบข้อมูลนี้",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'ใช่, ลบเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post('$deleteUrl', {id: id}, function(res) {
                if (res.success) {
                    Swal.fire(
                        'ลบแล้ว!',
                        'ข้อมูลถูกลบเรียบร้อย',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('ผิดพลาด', res.message, 'error');
                }
            }, 'json');
        }
    });
});
JS;
$this->registerJs($js);
?>

<?php Pjax::begin(['id' => 'grid-pjax']); ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <?=
        Html::button('<i class="fa-solid fa-arrow-left"></i> ย้อนกลับ', [
            'class' => 'btn btn-primary text-white font-weight-bold',
            'id' => 'btnBack',
            'type' => 'button'
        ]) ?>
    </div>

    <div>
        <?= Html::button('<i class="fa fa-plus"></i> เพิ่มข้อมูล', [
            'class' => 'btn btn-info font-weight-bold',
            'style' => 'background-color:#17a2b8; color:#fff; border-color:#17a2b8;',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#createModal',
            'data-url' => Url::to(['create-master']),
        ]) ?>


        <?= Html::button('<i class="fa fa-upload"></i> Import', [
            'class' => 'btn btn-success',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#importModal',
        ]) ?>


        <?= Html::button('<i class="fa-solid fa-download"></i> Export', [
            'class' => 'btn text-white font-weight-bold',
            'style' => 'background-color: #fd7e14; border-color: #fd7e14;',
            'data-bs-toggle' => 'modal',
            'data-bs-target' => '#exportModal',
        ]) ?>
    </div>
</div>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        [
            'class' => 'yii\grid\SerialColumn',
            'header' => 'No.',
            'headerOptions' => ['style' => 'width: 40px;'],
            'contentOptions' => ['style' => 'width: 40px; text-align: center;'],
        ],
        [
            'attribute' => 'item',
            'headerOptions' => ['style' => 'width: 80px;'],
            'contentOptions' => ['style' => 'width: 80px;'],
        ],
        [
            'attribute' => 'price',
            'format' => ['decimal', 2],
            'headerOptions' => ['style' => 'width: 20px; text-align: right;'],
            'contentOptions' => ['style' => 'width: 20px; text-align: right;'],
        ],
        [
            'attribute' => 'specification',
            'format' => 'raw',
            'headerOptions' => ['style' => 'width: 600px;'],
            'contentOptions' => ['style' => 'width: 600px; '],
            'value' => function ($model) {
                return nl2br(Html::encode($model->specification));
            }
        ],
        [
            'attribute' => 'short_name',
            'headerOptions' => ['style' => 'width: 80px;'],
            'contentOptions' => ['style' => 'width: 80px;'],
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'headerOptions' => ['style' => 'width: 80px; text-align: center;'],
            'template' => '{update} {delete}',
            'buttons' => [
                'update' => function ($url, $model) {
                    return Html::button('<i class="fa fa-edit"></i>', [
                        'class' => 'btn btn-sm btn-primary',
                        'title' => 'แก้ไข',
                        'data-bs-toggle' => 'modal',
                        'data-bs-target' => '#updateModal',
                        'data-id' => $model->id,
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a(
                        '<i class="fa fa-trash"></i>',
                        'javascript:void(0);',
                        [
                            'title' => 'ลบ',
                            'class' => 'btn btn-sm btn-danger btn-delete-master',
                            'data-id' => $model->id
                        ]
                    );
                },
            ],
        ]
    ],
    'pager' => [
        'options' => ['class' => 'pagination justify-content-center'],
        'linkOptions' => ['class' => 'page-link'],
        'activePageCssClass' => 'active',
        'disabledPageCssClass' => 'disabled',
        'maxButtonCount' => 12,
        'prevPageCssClass' => 'page-item',
        'nextPageCssClass' => 'page-item',
        'firstPageCssClass' => 'page-item',
        'lastPageCssClass' => 'page-item',
        'pageCssClass' => 'page-item',
        'disabledListItemSubTagOptions' => ['class' => 'page-link'],
        'prevPageLabel' => '&laquo;',
        'nextPageLabel' => '&raquo;',
    ],
]) ?>
<?php Pjax::end(); ?>

<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold" id="updateModalLabel">แก้ไขข้อมูลราคากลาง</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="modalContent"></div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title font-weight-bold" id="createModalLabel">เพิ่มข้อมูลใหม่</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="createContent"></div>
            </div>
        </div>
    </div>
</div>

<?php Modal::begin([
    'id' => 'importModal',
    'title' => '<h5 class="fw-bold text-start">Import ข้อมูลจาก Excel</h5>',
    'headerOptions' => ['class' => 'modal-header justify-content-start text-lg w-bold'],
    'size' => 'modal-lg',
    'options' => [
        'class' => 'fade',
        'tabindex' => '-1',
    ],
    'closeButton' => [
        'class' => 'btn-close',
        'data-bs-dismiss' => 'modal',
        'aria-label' => 'Close',
    ],
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => true,
    ],
]); ?>

<?php $form = ActiveForm::begin([
    'action' => ['import-excel'],
    'options' => ['enctype' => 'multipart/form-data', 'class' => 'p-3'],
]); ?>

<div class="mb-3">
    <?= $form->field(new \yii\base\DynamicModel(['excelFile']), 'excelFile')
        ->fileInput(['class' => 'form-control form-control-lg']) ?>
    <small class="text-muted">รองรับไฟล์ .xlsx เท่านั้น</small>
</div>

<div class="d-flex justify-content-end mt-4">
    <?= Html::submitButton('อัปโหลด', ['class' => 'btn btn-primary me-2']) ?>
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
</div>

<?php ActiveForm::end(); ?>
<?php Modal::end(); ?>

<?php
Modal::begin([
    'id' => 'exportModal',
    'title' => '<h5 class="fw-bold text-start">Export ข้อมูลเกณฑ์ราคากลาง</h5>',
    'headerOptions' => ['class' => 'modal-header justify-content-start text-lg w-bold'],
    'size' => 'modal-md',
    'options' => [
        'class' => 'fade',
        'tabindex' => '-1',
    ],
    'closeButton' => [
        'class' => 'btn-close',
        'data-bs-dismiss' => 'modal',
        'aria-label' => 'Close',
    ],
    'clientOptions' => [
        'backdrop' => 'static',
        'keyboard' => true,
    ],
]);
?>

<div class="d-flex flex-column gap-3 p-3">
    <?= Html::a('Export Excel', ['default/export-excel'], ['class' => 'btn btn-success']) ?>
    <!-- <?= Html::a('Export PDF', ['default/export-pdf'], ['class' => 'btn btn-danger']) ?> -->
    <div class="pt-4 text-end">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
    </div>
</div>

<?php Modal::end(); ?>