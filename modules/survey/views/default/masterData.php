<?php

use kartik\editable\Editable;
use kartik\grid\EditableColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
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
    <div>
        <?= Html::button('<i class="fa-solid fa-arrow-left"></i> ย้อนกลับ', [
            'class' => 'btn btn-primary text-white font-weight-bold',
            'id' => 'btnBack',
            'type' => 'button'
        ]) ?>
    </div>
</div>
<hr>

<?php
$updateUrl = Url::to(['update-master']);

$js = <<<JS
$('#updateModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    var id = button.data('id');

    $('#modalContent').html('กำลังโหลด...');
    $.get('{$updateUrl}', {id: id}, function(data){
        $('#modalContent').html(data);
    });
});
JS;

$this->registerJs($js);
?>


<?php
$deleteUrl = \yii\helpers\Url::to(['delete-master']);
$js = <<<JS
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
                            return \yii\helpers\Html::a(
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