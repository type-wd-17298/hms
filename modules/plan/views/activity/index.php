<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
//use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;

echo newerton\fancybox3\FancyBox::widget();
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'กิจกรรมสุขภาพ';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dep-activity-index">

    <h4 class="text-primary"><i class="fas fa-solid fa-photo-film"></i> <?= $this->title ?></h4>
    <?php Pjax::begin(); ?>

    <?=
    GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'heading' => '',
            'type' => '',
            'before' => $this->render('_search', ['model' => $dataProvider]),
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
        'responsiveWrap' => FALSE,
        'striped' => FALSE,
        'hover' => TRUE,
        'condensed' => TRUE,
        'export' => FALSE,
        'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
        'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
        'columns' => [
            //['class' => 'yii\grid\SerialColumn'],
            [
                'label' => 'วันที่ดำเนินการ',
                'attribute' => 'dep_activity_date',
                'format' => 'raw',
                //'vAlign' => 'middle',
                #'width' => '1%',
                'noWrap' => TRUE,
                #'hAlign' => 'right',
                'visible' => 1,
                'value' => function($model) {
                    return Ccomponent::getThaiDate(($model['dep_activity_date']), 'S', 1);
                }
            ],
            [
                'label' => 'รูปกิจกรรม',
                'noWrap' => TRUE,
                'format' => 'raw',
                //'width' => '30%',
                'visible' => 1,
                //'vAlign' => 'middle',
                'value' => function ($data) {
                    return '<div class="d-flex">' . $data->getThumbnailsView($data->dep_activity_id) . '</div>';
                },
            ],
            [
                'label' => 'วิดิโอกิจกรรม',
                'noWrap' => TRUE,
                'format' => 'raw',
                //'width' => '30%',
                'visible' => 1,
                //'vAlign' => 'middle',
                'value' => function ($data) {
                    $html = '<div class="embed-responsive embed-responsive-1by1 img-thumbnail" style="width: 50px;">
                <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' . $data['dep_activity_videoclip'] . '?rel=0"></iframe>
            </div>';
                    if (strlen($data['dep_activity_videoclip']) <> 11)
                        $html = '';

                    return $html;
                },
            ],
            [
                'attribute' => 'dep_activity_title',
                'format' => 'raw',
                //'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'attribute' => 'dep_activity_summary',
                'format' => 'raw',
                'contentOptions' => ['class' => 'small'],
                //'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'attribute' => 'dep_activity_purpose',
                'format' => 'raw',
                'contentOptions' => ['class' => 'small'],
                //'vAlign' => 'middle',
                'visible' => 1,
            ],
            [
                'label' => 'ผู้บันทึก',
                'attribute' => 'whoRecord.fullname',
                #'noWrap' => TRUE,
                'format' => 'raw',
                //'vAlign' => 'middle',
                'visible' => 0,
            ],
            [
                'label' => 'หน่วยงานผู้รับผิดชอบ',
                'attribute' => 'dep.department_name',
                'contentOptions' => ['class' => 'small'],
                #'noWrap' => TRUE,
                'format' => 'raw',
                //'vAlign' => 'middle',
                'visible' => 1,
                'value' => function ($data) {
                    return $data->department_code . ' ' . $data->dep->department_name;
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'width' => '2%',
                'visible' => 1,
                'noWrap' => TRUE,
                'urlCreator' => function ($action, \app\modules\survay\models\DepActivity $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'dep_activity_id' => $model->dep_activity_id]);
                }
            ],
        /*
          [
          'class' => ActionColumn::className(),
          'noWrap' => TRUE,
          'urlCreator' => function ($action, \app\modules\survay\models\DepActivity $model, $key, $index, $column) {
          return Url::toRoute([$action, 'dep_activity_id' => $model->dep_activity_id]);
          }
          ],
         *
         */
        ],
    ]);
    ?>

    <?php Pjax::end(); ?>

</div>
