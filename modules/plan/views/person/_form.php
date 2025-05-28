<?php

use yii\bootstrap4\Html;
#use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;
use yii\helpers\ArrayHelper;
use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\helpers\Url;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;

#use kartik\datetime\DateTimePicker;
#use app\modules\covid\models\ReportGroupType;
#use kartik\widgets\FileInput;

$lat = $model->person_gps_lat > 0 ? $model->person_gps_lat : 0;
$lng = $model->person_gps_lng > 0 ? $model->person_gps_lng : 0;

$js = <<<JS
        var map = null;
    		var marker = null;
    		var markers = [];

var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

function error(err) {
  console.warn('ERROR(\${err . code}): \${err . message}');
}
function getLocation() {
  if (navigator.geolocation) {
    var watchID = navigator.geolocation.getCurrentPosition(showPosition, error, options);
  } else {
    x.innerHTML = "Geolocation is not supported by this browser.";
        //alert('Geolocation is not supported by this browser.');
  }
          //navigator.geolocation.clearWatch(watchID);
}

function showPosition(position) {

        $('#person-person_gps_lat').val(position.coords.latitude);
        $('#person-person_gps_lng').val(position.coords.longitude);

        var myLatlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
              var myOptions = {
                zoom: 18,
                center: myLatlng,
                scrollwheel: true,
                zoomControl: true,
                //draggable: true,
                mapTypeId: google.maps.MapTypeId.ROADMAP
              }

              map = new google.maps.Map(document.getElementById("gmap"), myOptions);
                var marker = new google.maps.Marker({
                 label: 'คุณอยู่ตรงนี้',
                 //icon: 'https://icons.iconarchive.com/icons/icons-land/vista-map-markers/64/Map-Marker-Board-Chartreuse-icon.png',
                 position: new google.maps.LatLng(position.coords.latitude, position.coords.longitude),
                 map: map
               });

               map = new google.maps.Map(document.getElementById('gmap'), myOptions);
                if($lat != 0){
                    var setLatLng = new google.maps.LatLng($lat, $lng);
                    placeMarker(setLatLng, map);
                }else{
                    placeMarker(new google.maps.LatLng(position.coords.latitude, position.coords.longitude), map);
                   }

		google.maps.event.addListener(map, 'click', function(e) {
                    placeMarker(e.latLng, map);
                });


}

getLocation();

                  function placeMarker(position, map) {
				if (!marker) {
                                marker = new google.maps.Marker({
                                    position: position,
                                    draggable: true,
                                    map: map
                                      });

                            var lat = position.lat();
                            var lng = position.lng();

                            google.maps.event.addListener(marker, 'drag', function(e) {
		                var lat = e.latLng.lat();
		                var lng = e.latLng.lng();
                                    $('#person-person_gps_lat').val(lat);
                                    $('#person-person_gps_lng').val(lng);
		            });

		            map.panTo(position);
		            markers.push(marker);
    }
}


JS;
$this->registerJs($js, $this::POS_LOAD);
?>

<div class="person-form">
    <div class="alert alert-secondary">
        <div class="mb-0">
            <strong>คำแนะนำใช้งาน</strong><br>กรุณาเปิด <i class="fa-solid fa-location-crosshairs"></i> GPS
            ของเครื่องทุกครั้งที่ใช้งานระบบนี้
        </div>
    </div>
    <?php
    $form = ActiveForm::begin([
                'id' => 'frm',
                'type' => ActiveForm::TYPE_HORIZONTAL,
                'options' => ['enctype' => 'multipart/form-data'],
                'fieldConfig' => [
                    'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
                    'horizontalCssClasses' => [
                        #'columnSize' => 'lg',
                        'label' => 'col-sm-4',
                        'offset' => 'offset-sm-2',
                        'wrapper' => 'col-sm-8',
                        'error' => '',
                        'hint' => '',
                    ],
                ],
    ]);

    /*
      $form = ActiveForm::begin([
      'id' => 'frm',
      'options' => ['enctype' => 'multipart/form-data'],
      'layout' => 'horizontal',
      'fieldConfig' => [
      'template' => "{label}\n{beginWrapper}\n{input}\n{hint}\n{error}\n{endWrapper}",
      'horizontalCssClasses' => [
      'label' => 'col-sm-4',
      'offset' => 'offset-sm-2',
      'wrapper' => 'col-sm-8',
      'error' => '',
      'hint' => '',
      ],
      ],
      ]);
     *
     */
    #print_r($form->errorSummary($model))
    ?>

    <div id="gmap" style="height:250px;" class="mb-2">
        <div class="alert alert-danger" role="alert">
            <b class="text-center">
                กรุณาเปิด <i class="fa-solid fa-location-crosshairs"></i> GPS ในโทรศัพท์
            </b>
        </div>
    </div>
    <div class="card border-secondarys">
        <div class="card-header">
            <b>กรอกข้อมูลทั่วไป</b>
        </div>
        <div class="card-block m-2">

            <?= $form->field($model, 'person_gps_lat')->hiddenInput()->label(FALSE) ?>
            <?= $form->field($model, 'person_gps_lng')->hiddenInput()->label(FALSE) ?>


            <?php
            echo $form->field($model, 'department_code')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Cdepartment::find()
                                    ->orderBy(['department_code' => SORT_ASC])
                                    ->all(), 'department_code', 'department_name'),
                    [
                        'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => 'เลือกหน่วยงาน/สถาบัน',
                    ]
            );
            ?>
            <?php
            echo $form->field($model, 'person_type_id')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\PersonType::find()
                                    ->orderBy(['person_type_id' => SORT_ASC])
                                    ->all(), 'person_type_id', 'person_type_name'),
                    [
                        #'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--เลือกประเภทผู้คัดกรอง--',
                    ]
            );
            ?>
            <?php
            echo $form->field($model, 'person_cid')->widget(MaskedInput::className(), [
                'mask' => '9-9999-99999-99-9',
                'clientOptions' => [
                    'removeMaskOnSubmit' => true,
                ],
            ])
            ?>
            <?= $form->field($model, 'person_fullname')->textInput(['maxlength' => true]) ?>

            <?=
            $form->field($model, 'person_sex')->dropDownList(
                    ['1' => 'ชาย', '2' => 'หญิง'],
                    ['prompt' => 'เลือกเพศ', 'onchange' => 'getData()']
            );
            ?>
            <?=
            $form->field($model, 'person_birthdate')->widget(DatePicker::classname(), [
                'options' => ['placeholder' => 'วันเดือนปีเกิด'],
                'language' => 'th-TH',
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd',
                    'todayBtn' => true,
                    'todayHighlight' => true,
                #'yearRange' => '+543',
                ]
            ]);
            ?>
            <?php
            echo $form->field($model, 'person_status_id')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Cmstatus::find()
                                    ->orderBy(['mstatus' => SORT_ASC])
                                    ->all(), 'mstatus', 'mstatusdesc'),
                    [
                        #'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--เลือกสถานะ--',
                    ]
            );
            ?>
            <?php
            echo $form->field($model, 'person_occupation_id')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Coccupation::find()
                                    ->orderBy(['id_occupation' => SORT_ASC])
                                    ->all(), 'id_occupation', 'occupation'),
                    [
                        #'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--เลือกอาชีพ--',
                    ]
            );
            ?>
            <?php
            echo $form->field($model, 'person_education_id')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Ceducation::find()
                                    ->orderBy(['educationcode' => SORT_ASC])
                                    ->all(), 'educationcode', 'educationname'),
                    [
                        #'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--เลือกการศึกษา--',
                    ]
            );
            ?>
            <?php
            echo $form->field($model, 'person_religion_id')->dropDownList(
                    ArrayHelper::map(app\modules\survay\models\Creligion::find()
                                    ->orderBy(['id_religion' => SORT_ASC])
                                    ->all(), 'id_religion', 'religion'),
                    [
                        #'disabled' => $model->isNewRecord ? false : true,
                        'prompt' => '--เลือกศาสนา--',
                    ]
            );
            ?>



            <?= $form->field($model, 'person_tel')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'person_address_no')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'person_address_moo')->textInput(['maxlength' => true]) ?>
            <?php
            echo $form->field($model, 'person_address_code')->widget(Select2::classname(), [
                'initValueText' => @(isset($model->person_address_code) ? \app\modules\survay\models\Thaiaddress::findOne($model->person_address_code)->fullname : ''), // set the initial display text
                'options' => ['placeholder' => 'Click เพื่อพิมพ์ชื่อตำบลหรืออำเภอหรือจังหวัด',],
                //'size' => Select2::MEDIUM,
                //'addon' => $addon,
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
                    'language' => [
                        'errorLoading' => new JsExpression("function () { return 'Waiting for results...'; }"),
                    ],
                    'ajax' => [
                        'url' => Url::to(['autosearch']),
                        'dataType' => 'json',
                        'data' => new JsExpression('function(params) { return {q:params.term}; }')
                    ],
                    'escapeMarkup' => new JsExpression('function(markup) { return markup; }'),
                    'templateResult' => new JsExpression('function(data) { return data.text; }'),
                    'templateSelection' => new JsExpression('function(data) { return data.text; }'),
                ],
            ]);
            ?>

            <?PHP # $form->field($model, 'person_income')->textInput()  ?>

            <?=
            $form->field($model, 'person_chronic')->radioList([
                0 => 'ไม่มี',
                1 => 'โรคความดันโลหิตสูง',
                2 => 'โรคเบาหวาน',
                3 => 'โรคเบาหวานและความดันโลหิตสูง',
                9 => 'โรคอื่นๆ',
            ])
            ?>


        </div>
    </div>

    <div class="row justify-content-between mt-3 mb-5">
        <div class="col-6">
            <?= Html::submitButton('บันทึกรายการ', ['class' => 'btn btn-primary btn-lg']) ?>
        </div>
        <div class="col-6 text-right">
            <?= Html::a('กลับหน้าจัดการ', ['index'], ['class' => 'btn btn-light btn-lg']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div>