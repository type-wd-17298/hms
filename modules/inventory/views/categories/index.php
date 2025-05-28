<?php

use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\bootstrap4\ActiveForm;
use kartik\widgets\Select2;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'หมวดหมู่สินค้า';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-categories-index">
    <?php Pjax::begin(); ?>
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <h4 class="card-title">สร้างหมวดหมู่สินค้าใหม่</h4>
                        <?php
                        $form = ActiveForm::begin([
                                    'action' => (isset($_GET['id']) ? ['update', 'id' => $_GET['id']] : ['create']),
                                    'enableClientValidation' => true,
                                    'enableAjaxValidation' => true,
                        ]);
                        ?>
                        <?= $form->field($model, 'categories_name')->textInput(['maxlength' => true]) ?>
                        <?php
                        $escape = new JsExpression("function(m) { return m; }");

                        echo $form->field($model, 'categories_group')->widget(Select2::classname(), [
                            #'initValueText' => $hospcodeDesc, // set the initial display text
                            'data' => ArrayHelper::map($attribute, 'categories_id', 'categories_title'),
                            'options' => ['placeholder' => 'เลือกหมวดหมู่หลัก...'],
                            'pluginOptions' => [
                                'escapeMarkup' => $escape,
                                'allowClear' => true,
                                'minimumInputLength' => 0,
                            ],
                        ]);
                        ?>
                        <div class="form-group">
                            <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary']) ?>
                            <?= Html::a('ยกเลิก', ['index'], ['class' => 'btn btn-outline-danger']) ?>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">หมวดหมู่รายการวัสดุ/ครุภัณฑ์</h4>
                    <?=
                    GridView::widget([
                        'dataProvider' => $dataProvider,
                        'bordered' => FALSE,
                        'striped' => FALSE,
                        'condensed' => TRUE,
                        'responsiveWrap' => FALSE,
                        'hover' => FALSE,
                        'layout' => '<div class=""><div>{items}</div></div>',
                        'columns' => [
                            ['class' => 'kartik\grid\SerialColumn'],
                            [
                                'label' => 'หมวดหมู่รายการ',
                                'attribute' => 'categories_name',
                                'format' => 'html',
                                'value' => function ($model) {
                                    $addString = '';
                                    switch ($model['categories_level']) {

                                        case 1 :
                                            $addString = '<i class="feather icon-more-vertical"></i> <b>' . $model['categories_name'] . '</b>';
                                            break;
                                        case 2 :
                                            $addString = '&nbsp;&nbsp;&nbsp;<i class="feather icon-chevron-right"></i> ' . $model['categories_name'];
                                            break;
                                        case 3 :
                                            $addString = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="feather icon-chevron-right"></i> ' . $model['categories_name'];
                                            break;
                                    }

                                    return Html::a($addString, ['index', 'id' => $model['categories_id']]);
                                },
                            ],
                            [
                                'label' => 'CODE',
                                'attribute' => 'categories_code',
                            ],
                            ['class' => 'kartik\grid\ActionColumn',
                                'contentOptions' => ['style' => 'width:10%;'],
                                'header' => 'ดำเนินการ',
                                'noWrap' => TRUE,
                                'template' => '{all}',
                                'buttons' => [
                                    'all' => function ($url, $model, $key) {
                                        return
                                        kartik\bs4dropdown\ButtonDropdown::widget([
                                            'encodeLabel' => FALSE,
                                            'label' => 'ดำเนินการ',
                                            'direction' => 'left',
                                            'dropdown' => [
                                                'encodeLabels' => false,
                                                'items' => [
                                                    ['label' => '<i class="feather icon-edit"></i> แก้ไขรายการ', 'url' => ['index', 'id' => $key]],
                                                    '<div class="dropdown-divider"></div>',
                                                    ['label' => '<i class="feather icon-trash-2"></i> ลบรายการ',
                                                        'linkOptions' => [
                                                            'data' => [
                                                                'method' => 'post',
                                                                'confirm' => 'ยืนยันการลบข้อมูลนี้หรือไม่ ?',
                                                            ],
                                                        ],
                                                        'url' => ['delete', 'id' => $model['categories_id']],
                                                    ],
                                                ],
                                            ],
                                            'buttonOptions' => ['class' => 'btn-default btn-sm btn-outline-light waves-effect waves-light']]);
                                    },
                                ],
                            ],
                        ],
                    ]);
                    ?>
                </div>
            </div>
        </div>
    </div>


    <?php Pjax::end(); ?>

</div>
