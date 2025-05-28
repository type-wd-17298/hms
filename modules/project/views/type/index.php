<?php

//use app\modules\project\models\ProjectType;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'ประเภทรายการ';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['/project/type']);
Pjax::begin(['id' => 'pjax-type', 'timeout' => false, 'enablePushState' => false]);
$js = <<<JS
     $('.pjax-delete-link').on('click', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('delete-url');
            //var pjaxContainer = $(this).attr('pjax-container');
            var result = confirm('Delete this item, are you sure?');
            if(result) {
                $.ajax({
                    url: deleteUrl,
                    type: 'post',
                    error: function(xhr, status, error) {
                        alert('There was an error with your request.' + xhr.responseText);
                    }
                }).done(function(data) {
                    $.get("{$url}",{}, function(data) {
                        $("#modalContentType").html(data);
                    });
                });
            }
        });
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="project-type-index">

    <?php
    echo $this->render('_form', ['model' => $model]);
    ?>
    <div class="card mt-2">
        <?=
        GridView::widget([
            'panel' => [
                'heading' => Html::encode($this->title),
                'type' => '',
                'before' => Html::a('เพิ่มรายการ', ['create'], ['class' => 'btn btn-success']),
                'footer' => false,
            ],
            'pjax' => 0,
            'panelTemplate' => '<div class="">
            {items}
            {panelAfter}
            {panelFooter}
            <div class="text-center m-2">{summary}</div>
            <div class="text-center m-2">{pager}</div>
            </div>',
            'responsiveWrap' => FALSE,
            'striped' => FALSE,
            'hover' => TRUE,
            'bordered' => FALSE,
            'condensed' => TRUE,
            'showPageSummary' => FALSE,
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'exportContainer' => ['class' => 'btn-group mr-2'],
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'attribute' => 'project_type_name',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        return '' . Html::a($model->project_type_name, ['index', 'id' => $model->project_type_id], ['data-pjax' => 1]);
                    }
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{delete}',
                    'noWrap' => TRUE,
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('ลบข้อมูล', false, [
                                'class' => 'btn btn-danger btn-sm pjax-delete-link',
                                'data-pjax' => 1,
                                'delete-url' => $url,
                                //'pjax-container' => 'pjax-type',
                                'title' => 'ลบข้อมูล'
                            ]);
                        }
                    ],
                ],
            ],
        ]);
        ?>
    </div>
</div>
<?php Pjax::end(); ?>