<?php

//use app\modules\project\models\ProjectType;
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */
$this->title = 'จัดการข้อมูลตำแหน่งบริหาร';
$this->params['breadcrumbs'][] = $this->title;
$url = Url::to(['/project/company']);
Pjax::begin(['id' => 'pjax-company', 'timeout' => false, 'enablePushState' => false]);
$js = <<<JS
     $('.pjax-delete-link').on('click', function(e) {
            e.preventDefault();
            var deleteUrl = $(this).attr('delete-url');
            //var pjaxContainer = $(this).attr('pjax-container');
            var result = confirm('Delete this item, are you sure?');
            if(result) {
                $.ajax({
                    url: deleteUrl,
                    company: 'post',
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

<div class="project-company-index">

    <?php
    echo $this->render('_form', ['model' => $model]);
    ?>
    <div class="card mt-2">
        <?=
        GridView::widget([
            'panel' => [
                'heading' => Html::encode($this->title),
                'company' => '',
                'before' => Html::a('เพิ่มรายการ', ['index'], ['class' => 'btn btn-success']),
                'footer' => false,
            ],
            'panelTemplate' => '<div class="">
          {panelBefore}
          {items}
          {panelAfter}
          {panelFooter}
          <div class="text-center m-2">{summary}</div>
          <div class="text-center m-2">{pager}</div>
          </div>',
            'pjax' => 0,
            'responsiveWrap' => FALSE,
            'striped' => TRUE,
            'hover' => TRUE,
            'bordered' => FALSE,
            'condensed' => TRUE,
            'export' => FALSE,
            'toggleDataContainer' => ['class' => 'btn-group mr-2 d-sm-none  d-none'],
            'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
            'showPageSummary' => FALSE,
            'toggleDataContainer' => ['class' => 'btn-group mr-2'],
            'exportContainer' => ['class' => 'btn-group mr-2'],
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'attribute' => 'employee_executive_code',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'format' => 'raw',
                ],
                [
                    'headerOptions' => ['class' => 'font-weight-bold'],
                    'contentOptions' => ['class' => 'font-weight-bold'],
                    'attribute' => 'employee_executive_name',
                    'vAlign' => 'middle',
                    'format' => 'raw',
                    'value' => function ($model) {
                        $html = '';
                        $checkDep = $model->getDep();
                        foreach ($checkDep as $value) {
                            $html .= Html::tag('span', $value->department->employee_dep_label, ['class' => 'badge badge-xs light badge-primary small mr-1']);
                        }
                        return '' . Html::a($model->employee_executive_name, ['index', 'id' => $model->employee_executive_id], ['data-pjax' => 1])
                        . (!empty($html) ? '<br>' . $html : '') . ($model->employee_executive_comment ? '<br>' . Html::tag('div', $model->employee_executive_comment, ['class' => 'badge-xs badge text-dark']) : '');
                    }
                ],
                [
                    'attribute' => 'employee_executive_level',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'format' => 'raw',
                ],
                [
                    'attribute' => 'employee_executive_sort',
                    'vAlign' => 'middle',
                    'hAlign' => 'center',
                    'format' => 'raw',
                ],
                [
                    'class' => 'kartik\grid\ActionColumn',
                    'template' => '{delete}',
                    'visible' => 0,
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            return Html::a('ลบข้อมูล', false, [
                                'class' => 'btn btn-danger btn-sm pjax-delete-link',
                                'data-pjax' => 1,
                                'delete-url' => $url,
                                //'pjax-container' => 'pjax-company',
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