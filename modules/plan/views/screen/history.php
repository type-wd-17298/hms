<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
#use yii\grid\ActionColumn;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use app\modules\survay\models\Thaiaddress;
use app\modules\survay\components\Cprocess;
use app\modules\survay\components\Cmapclient;

$this->title = 'รายการสำรวจข้อมูลสุขภาพ';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['screen/history']);
$js = <<<JS

JS;
//$this->registerJs($js, $this::POS_READY);
//$this->registerJsFile('//maps.googleapis.com/maps/api/js?key=' . Yii::$app->params['googleMapToken']);
$address = $data->person_address_no .
        ($data->person_address_moo <> '' ? ' ม.' . $data->person_address_moo : '')
        . @(isset($data->person_address_code) ? ' ' . Thaiaddress::findOne($data->person_address_code)->full_name : '');
?>
<div class="alert alert-dismissible alert-warning">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4 class="alert-heading"><?= $data->person_fullname ?></h4>
    <p class="mb-0">
        <b>เพศ</b> <?= $data->person_sex == 1 ? 'ชาย' : 'หญิง' ?>
        <b>อายุ</b> <?= (date('Y') - substr($data->person_birthdate, 0, 4)) . ' ปี'; ?>
        <br><b>ที่อยู่</b> <?= $address ?>
    </p>
</div>

<div class="person-screen-index">
    <h4 class="text-primary"><i class="fas fa-solid fa-users-rectangle"></i> <?= $this->title ?></h4>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#home">ประวัติการสำรวจ</a>
        </li>
        <li class="nav-item d-none">
            <a class="nav-link" data-toggle="tab" href="#profile">สถิติ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#map">แผนที่บ้าน</a>
        </li>
    </ul>
    <div id="myTabContent" class="tab-content">
        <div class="tab-pane fade show active" id="home">
            <div class="mt-1">

                <?php Pjax::begin(['timeout' => false, 'enablePushState' => false]); ?>
                <?PHP
                echo GridView::widget([
//                    'panel' => [
//                        'heading' => '',
//                        'type' => '',
//                        #'before' => $this->render('_search', ['model' => $dataProvider]),
//                        'footer' => false,
//                    ],
//                    'panelTemplate' => '<div class="">
//  {panelBefore}
//  {items}
//  {panelAfter}
//  {panelFooter}
//  <div class="text-center m-2">{summary}</div>
//  <div class="text-center m-2">{pager}</div>
//  </div>',

                    'layout' => '{items}{pager}',
                    'responsiveWrap' => FALSE,
                    'striped' => FALSE,
                    'hover' => TRUE,
                    'condensed' => TRUE,
                    'export' => FALSE,
                    'toggleDataContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block '],
                    'exportContainer' => ['class' => 'btn-group mr-2 d-none d-xl-block'],
                    'dataProvider' => $dataProvider,
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],
                        [
                            'label' => 'ผลการคัดกรอง HT',
                            'attribute' => 'person_screen_result',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'width' => '5%',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'value' => function ($model) {
                                return Cprocess::calResult($model['color_ht']);
                            },
                        ],
                        [
                            'label' => 'ผลการคัดกรอง DM',
                            'attribute' => 'person_screen_result',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'width' => '5%',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'value' => function ($model) {
                                return Cprocess::calResult($model['color_dm']);
                            },
                        ],
                        [
                            'label' => 'วันที่สำรวจ',
                            'attribute' => 'person_screen_date',
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            #'width' => '1%',
                            'noWrap' => TRUE,
                            #'hAlign' => 'right',
                            'visible' => 1,
                            'value' => function($model) {
                                return Ccomponent::getThaiDate(($model['person_screen_date']), 'S', 1);
                            }
                        ],
                        [
                            'label' => 'กลุ่มสำรวจ',
                            'attribute' => 'person.person_type_id',
                            'noWrap' => TRUE,
                            #'width' => '5%',
                            'visible' => 0,
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'value' => function ($model) {
                                return $model['person']['personType']['person_type_name'];
                            },
                        ],
                        [
                            'label' => 'ชื่อ-นามสกุล',
                            'attribute' => 'person_id',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'visible' => 0,
                            'value' => function ($model) {
                                #return $model['person']['person_fullname'];
                                return '<div class="font-weight-bold">' . Html::a($model['person']['person_fullname'] . '</div>', ['person/update', 'id' => $model['person']['person_id']]);
                            },
                        ],
                        [
                            'attribute' => 'person.person_sex',
                            #'width' => '10%',
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'visible' => 0,
                            'value' => function ($model) {
                                return $model['person']['person_sex'] == 1 ? 'ชาย' : 'หญิง';
                            },
                        ],
                        [
                            'attribute' => 'person.person_age',
                            #'width' => '10%',
                            'noWrap' => TRUE,
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'value' => function ($model) {
                                return (substr($model['person_screen_date'], 0, 4) - substr($model['person']['person_birthdate'], 0, 4)) . ' ปี';
                            },
                        ],
                        [
                            'attribute' => 'person_screen_bmi',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return @number_format($model->person_screen_weight / pow(($model->person_screen_height / 100), 2), 2);
                            },
                        ],
                        [
                            'attribute' => 'person_screen_weight',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                        ],
                        [
                            'attribute' => 'person_screen_height',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                        ],
                        [
                            'label' => 'โรคประจำตัว',
                            'attribute' => 'person.person_chronic',
                            #'width' => '10%',
                            'noWrap' => TRUE,
                            'vAlign' => 'middle',
                            'hAlign' => 'center',
                            'value' => function ($model) {
                                return $model['person']['person_chronic'] == 1 ? 'มี' : 'ไม่มี';
                            },
                        ],
                        [
                            'label' => 'ความดันโลหิต',
                            'attribute' => 'person_screen_sbp',
                            'noWrap' => TRUE,
                            'hAlign' => 'center',
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return $model->person_screen_sbp . '/' . $model->person_screen_dbp;
                            },
                        ],
                        [
                            'label' => 'ค่าน้ำตาลในเลือด',
                            'attribute' => 'person_screen_fbs',
                            'noWrap' => TRUE,
                            'hAlign' => 'center',
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'value' => function ($model) {
                                return $model->person_screen_fbs . ' mg/dL';
                            }
                        ],
                        [
                            'attribute' => 'person_screen_pulse',
                            'noWrap' => TRUE,
                            'visible' => 0,
                            'hAlign' => 'center',
                            'format' => 'raw',
                            'vAlign' => 'middle',
                        ],
                        [
                            'label' => 'ผู้สำรวจ',
                            'attribute' => 'whoRecord.fullname',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'visible' => 1,
                        ],
                        [
                            'label' => 'หน่วยงานผู้รับผิดชอบ',
                            'attribute' => 'person.dep.department_name',
                            'noWrap' => TRUE,
                            'format' => 'raw',
                            'vAlign' => 'middle',
                            'visible' => 1,
                        ],
                    ],
                ]);
                ?>

                <?php Pjax::end(); ?>

            </div>
        </div>
        <div class="tab-pane fade" id="profile">

        </div>
        <div class="tab-pane fade" id="map">
            <?php
            #echo Cmap::widget(['zoom' => 10, 'fillColor' => '#FFE4B5', 'height' => 500, 'showHosp' => ['hos']]);
            $point = [['person_name' => $data->person_fullname,
            'address_name' => $address,
            'lat' => $data->person_gps_lat,
            'lng' => $data->person_gps_lng
            ]];
            echo Cmapclient::widget(['point' => $point, 'zoom' => 16, 'height' => 500]);
            ?>
        </div>

    </div>
</div>
