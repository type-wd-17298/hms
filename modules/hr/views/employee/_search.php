<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\form\ActiveForm;
use kartik\daterange\DateRangePicker;

$url = Url::to(['default/executive#profile']);

$js1 = <<<JS
    $("#screen_date").keypress(function (e) {
        alert('keypress');
        //return false;
    });
    $("#screen_date").keydown(function (e) {
        //return false;
         alert('keydown');
    });
JS;
//$this->registerJs($js, $this::POS_READY);
//Check การเสียบบัตรค้างอยู่

$js = <<<JS
var mqttCard;
var reconnectTimeout = 2000;
var hostCard = '127.0.0.1';
var portCard = 10884;
var out_msg = '';
function getSmartCard(msg){
        const obj = JSON.parse(msg.payloadString);
        if(obj.status != 'CARD_EXITED'){
            if(obj.status == 'DATA_RETRIEVED'){
                //console.log(obj.data);
                    var cid = obj.data.cid;
                    $('#inputSearch').val(cid);
                    $('#frmIndex').submit();
                    //-----------------------------------------------------------------
                    $("#modalContents").html('');
                    $('#modalForm').modal('show');
                    $.get("{$url}",{id:cid,var:obj.data},  function(data) {
                        $("#modalContents").html(data);
                    });
            }
            if(obj.status == 'IMAGE_RETRIEVED'){
                //console.log(obj.data);
            }
        }else{
            //console.log(obj.status);
                    $("#modalContents").html('');
                    $('#modalForm').modal('hide');
                    $('#inputSearch').val('');
                    $('#frmIndex').submit();
        }
}

function sub_topicsCard(){
	var stopic= 'moph/ict/mqtt';
	var soptions={qos:0};
	mqttCard.subscribe(stopic,soptions);
	return false;
}
function onCardConnect(data){
    $('#reportSmartCard').html('<div class="alert alert-primary mt-2"><h4 class="alert-heading">ระบบพร้อมทำงานใน Mode Scan!</h4><p class="mb-0">คุณสามารถเสียบบัตรประชาชนได้เลยค่ะ ระบบพร้อมรับข้อมูลการบันทึกจากเครื่องอ่าน Smart Card</p></div>');
    console.log("Connected ");
    sub_topicsCard();
}
function onCardFailure(){
        $('#reportSmartCard').html('<div class="alert alert-warning mt-2"><h4 class="alert-heading">พบข้อผิดพลาด!</h4><p class="mb-0">มีข้อผิดพลาด กรุณาตรวจสอบ Smart Card</p></div>');
        console.log('Connection Attemp to Host '+hostCard+' Failed');
        setTimeout(MQTTCardConnect,reconnectTimeout);
}
function onMessageCardArrived(msg){
        getSmartCard(msg);
}
function MQTTCardConnect(){
    mqttCard = new Paho.MQTT.Client(hostCard,portCard,'clientjs');
    var options = {
        timeout:5,
        onSuccess: onCardConnect,
        onFailure: onCardFailure,
    };
    mqttCard.onMessageArrived =  onMessageCardArrived;
    mqttCard.connect(options);
}
MQTTCardConnect();

JS;
$this->registerJs($js, $this::POS_READY);
?>

<div class="person-search">
    <form id="frmIndex" class="form-inline1" data-pjax="true">
        <?php
        /*
          $form = ActiveForm::begin([
          'id' => 'frmIndex',
          'action' => ['index'],
          'method' => 'get',
          'options' => [
          'data-pjax' => true,
          'class' => 'form-inline'
          ],
          ]);
         *
         */
        ?>
        <div class="mb-3">
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="1" checked="" name="status_active" onclick="$('#view').val(1);$('#frmIndex').submit();">สถานะใช้งานปกติ
                </label>
            </div>
            <div class="form-check form-check-inline">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" value="0" name="status_inactive">สถานะปิดการใช้งาน
                </label>
            </div>
        </div>
        <div class="input-group input-group-md mr-2">

            <input type="text" class="form-control form-control-lg" id="inputSearch"  name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >
            <?PHP
            echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\hr\models\EmployeeDep::find()->where(['employee_dep_status' => 1])->joinWith('type')->orderBy(['category_id' => 'ASC'])->asArray()->all(), 'employee_dep_id', 'employee_dep_label', 'type.category_name'),
                    [
                        'class' => 'form-control form-control-lg ', //d-none d-xl-block
                        'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                        'width' => '10',
            ]);
            ?>
            <input type="hidden"  id="view"  name="view" value="<?= @$_GET['view'] ?>"  >
            <input type="hidden"  id="type"  name="type" value="<?= @$_GET['type'] ?>"  >
            <div class="input-group-append">
                <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> <b>แสดงรายงาน</b>', ['class' => 'btn btn-primary', 'onclick' => '$("#view").val("");']) ?>
                <button class="btn btn-dark font-weight-bold" type="button" data-bs-toggle="dropdown" aria-expanded="false">กลุ่มเจ้าหน้าที่</button>
                <div class="dropdown-menu" style="">
                    <?= Html::button('<i class="fa-regular fa-rectangle-list"></i> <b>ผู้อำนวยการ</b>', ['class' => 'btn btn-dark', 'onclick' => '$("#view").val(1);$("#frmIndex").submit();']) ?>
                    <?= Html::button('<i class="fa-regular fa-rectangle-list"></i> <b>รองผู้อำนวยการ</b>', ['class' => 'btn btn-dark', 'onclick' => '$("#view").val(2);$("#frmIndex").submit();']) ?>
                    <?= Html::button('<i class="fa-regular fa-rectangle-list"></i> <b>หัวหน้ากลุ่มงาน</b>', ['class' => 'btn btn-dark', 'onclick' => '$("#view").val(3);$("#frmIndex").submit();']) ?>
                    <?= Html::button('<i class="fa-regular fa-rectangle-list"></i> <b>หัวหน้างาน/ศูนย์</b>', ['class' => 'btn btn-dark', 'onclick' => '$("#view").val(4);$("#frmIndex").submit();']) ?>
                </div>
                <?= Html::button('<i class="fa-regular fa-plus"></i> <b>เพิ่มผู้ใช้งาน</b>', ['class' => 'btn btn-secondary active']) ?>
                {export}
            </div>
        </div>
    </form>
    <?php #ActiveForm::end();    ?>
</div>
