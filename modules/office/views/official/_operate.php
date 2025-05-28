<?php

use kartik\widgets\Select2;
use yii\web\JsExpression;
use yii\bootstrap4\Html;
//use kartik\form\ActiveForm;
//use yii\helpers\ArrayHelper;
use yii\helpers\Url;
//use kartik\date\DatePicker;
//use kartik\widgets\FileInput;
use yii\widgets\Pjax;
use app\components\Ccomponent;
use kartik\grid\GridView;

$defaultPage = '';

$urlAcl = Url::to(['acknowledge']);
$urlOperate = Url::to(['operate']);
$src = Url::to(['view', 'id' => @$modelProcess->leave_id]);
$url = Url::to(['createprocess']);
$js = <<<JS
/*
$(".ckItems").click(function(e){
        var ck = $('input[name=frmStatus]:checked', '#frm999').val();
        if(ck == 'F3' || ck == 'F18' || ck == 'F19'){
           $('#receiverID').prop('disabled', false);
        }else{
           $('#receiverID').prop('disabled', true);
           $('#receiverID').val(null).trigger('change');
        }
});
*/
$(".btnReload").click(function(){
        $("#embedContent").html('');
        $("#embedContent").append('<div class="embed-responsive embed-responsive-16by9"><embed class="embed-responsive-items" src="{$src}#view=FitH" type="application/pdf" /></div>');
});

JS;
$this->registerJs($js, $this::POS_READY);
Pjax::begin(['id' => 'frm01', 'timeout' => false, 'enablePushState' => false]);
?>
<!-- Nav tabs -->
<div class="default-tab">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> เอกสาร</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#status"><i class="la la-bookmark me-2"></i> สถานะเอกสาร <span class="badge badge-primary badge-sm"></span></a>
        </li>
        <!--        <li class="nav-item">
                    <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#message"><i class="la la-envelope me-2"></i> Messages <span class="badge badge-danger badge-sm">99</span></a>
                </li>-->
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <form id="frm999">
                <div class="row">
                    <div class="col-md-8">
                        <div class="pt-2">
                            <div>
                                <div class="embed-responsive embed-responsive-1by1">
                                    <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['view', 'id' => @$modelProcess->leave_id]) ?>#view=FitH" type="application/pdf" /></iframe>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card1">
                            <div class="pt-2">
                                <div class="h4 font-weight-bold">การดำเนินการ</div>
                                <div class="mb-3 row">
                                    <div class="col-sm-12">
                                        <div class="alert alert-primary">
                                            <strong>ผู้ยื่นใบลา : <?= $modelProcess->emps->employee_fullname ?></strong>
                                        </div>
                                        <div class="alert alert-warning">
                                            <b>สถานะหนังสือ</b> : <?= @$modelProcess->leaveStatus->leave_status_name ?>
                                            <?= @$headHtml ?>
                                            <hr>

                                        </div>

                                        <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnAcknowledge"><i class="fa-solid fa-paper-plane"></i> รับทราบ</button>

                                    </div>
                                </div>
                                <fieldset class="mb-3 d-none">
                                    <div class="row">
                                        <label class="col-form-label col-sm-12 pt-0 font-weight-bold">คำอธิบาย</label>
                                        <div class="col-sm-12">
                                            <textarea  rows="5" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="mb-3 row">
                                    <div class="col-sm-6 font-weight-bold">ส่งดำเนินการต่อ/ส่งคืน</div>
                                    <div class="col-sm-6">
                                        <div class="form-check ckItems">
                                            <input class="form-check-input" type="radio" name="frmStatus" value="F3" id="defaultCheck1" checked="">
                                            <label class="form-check-label font-weight-bold" for="defaultCheck1">
                                                ดำเนินการต่อ
                                            </label>
                                        </div>
                                        <div class="form-check ckItems">
                                            <input class="form-check-input" type="radio" name="frmStatus" value="F18" id="defaultCheck5">
                                            <label class="form-check-label" for="defaultCheck5">
                                                รองผู้อำนวยการ
                                            </label>
                                        </div>
                                        <div class="form-check ckItems">
                                            <input class="form-check-input" type="radio" name="frmStatus" value="F19" id="defaultCheck6">
                                            <label class="form-check-label" for="defaultCheck6">
                                                ผู้อำนวยการ
                                            </label>
                                        </div>
                                        <div class="form-check ckItems" >
                                            <input class="form-check-input" type="radio" name="frmStatus" value="FF" id="defaultCheck8">
                                            <label class="form-check-label text-success" for="defaultCheck8">
                                                อนุมัติการลา
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <fieldset class="mb-3">
                                    <div class="row">
                                        <label class="col-form-label col-sm-12 pt-0 font-weight-bold">หมายเหตุ/ความเห็น</label>
                                        <div class="col-sm-12">
                                            <textarea name="comment"  rows="10" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </fieldset>
                                <div class="mb-3 row">
                                    <div class="col-sm-12">
                                        <input type="hidden" name="id" value="<?= @$model->leave_id ?>">
                                        <button type="button" class="btn btn-primary btn-block font-weight-bold btn-lg" id="btnConf"><i class="fa-regular fa-clone fa-1x"></i> ดำเนินการ</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade small" id="status">

        </div>
    </div>
</div>

<?php Pjax::end() ?>
