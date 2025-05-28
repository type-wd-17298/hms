<?php

use yii\helpers\Html;
use yii\helpers\Url;
//use yii\grid\ActionColumn;
use yii\widgets\Pjax;
use kartik\grid\GridView;
use app\components\Ccomponent;

$js = <<<JS
setInterval(function(){
         $.pjax.reload({container: '#pjManageMonitor', async: false});
 }, 1000);

JS;
$this->registerJs($js, $this::POS_READY);
//Pjax::begin(['id' => 'pjManage', 'timeout' => false, 'enablePushState' => false]); //
Pjax::begin(['id' => 'pjManageMonitor', 'timeout' => false, 'enablePushState' => false]); //
?>

<div class="row page-titles">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="javascript:void(0)">สารบรรณกลาง</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="javascript:void(0)"><?= date('H:m:s') ?></a></li>
    </ol>
</div>
<div class="card">
    <div class="card-body">
        <div class="row shapreter-row">
            <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                <div class="static-icon">
                    <span>
                        <i class="fas fa-eye"></i>
                    </span>
                    <h2 class="count"><?= rand(1, 1000) ?></h2>
                    <span class="fs-14">สารบรรณกลาง</span>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                <div class="static-icon">
                    <span>
                        <i class="far fa-comments"></i>
                    </span>
                    <h2 class="count"><?= rand(1, 1000) ?></h2>
                    <span class="fs-14">หัวหน้ากลุ่มงานบริหาร</span>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                <div class="static-icon">
                    <span>
                        <i class="fas fa-suitcase"></i>
                    </span>
                    <h2 class="count"><?= rand(1, 1000) ?></h2>
                    <span class="fs-14">งานเลขา</span>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                <div class="static-icon">
                    <span>
                        <i class="fas fa-suitcase"></i>
                    </span>
                    <h2 class="count"><?= rand(1, 100) ?></h2>
                    <span class="fs-14">รองผู้อำนวยการ</span>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                <div class="static-icon">
                    <span>
                        <i class="fas fa-calendar"></i>
                    </span>
                    <h2 class="count"><?= rand(1, 100) ?></h2>
                    <span class="fs-14">ผู้อำนวยการ</span>
                </div>
            </div>
            <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                <div class="static-icon">
                    <span>
                        <i class="fas fa-phone-alt"></i>
                    </span>
                    <h2 class="count"><?= rand(1, 100) ?></h2>
                    <span class="fs-14">เอกสารส่งแก้ไข</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php Pjax::end(); ?>
<!-- Nav tabs -->
<div class="default-tab card">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="fa-regular fa-folder"></i>&nbsp;&nbsp;แฟ้มหนังสือราชการ</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#folder01"><i class="fa-regular fa-folder"></i>&nbsp;&nbsp;แฟ้มบันทึกเสนอ </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#folder02"><i class="fa-regular fa-folder"></i>&nbsp;&nbsp;แฟ้มหนังสือเวียน </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#folder03"><i class="fa-regular fa-folder"></i>&nbsp;&nbsp;แฟ้มคำสั่งราชการ </a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#folder04"><i class="fa-regular fa-folder"></i>&nbsp;&nbsp;แฟ้มรับหนังสือภายใน </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row shapreter-row">
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-eye"></i>
                                </span>
                                <h2 class="count">94</h2>
                                <span class="fs-14">สารบรรณกลาง</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="far fa-comments"></i>
                                </span>
                                <h2 class="count">261</h2>
                                <span class="fs-14">หัวหน้ากลุ่มงานบริหาร</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">581</h2>
                                <span class="fs-14">งานเลขา</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">25</h2>
                                <span class="fs-14">รองผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <h2 class="count">221</h2>
                                <span class="fs-14">ผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                                <h2 class="count">4</h2>
                                <span class="fs-14">เอกสารส่งแก้ไข</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade show " id="folder01" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row shapreter-row">
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-eye"></i>
                                </span>
                                <h2 class="count">94</h2>
                                <span class="fs-14">สารบรรณกลาง</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="far fa-comments"></i>
                                </span>
                                <h2 class="count">261</h2>
                                <span class="fs-14">หัวหน้ากลุ่มงานบริหาร</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">581</h2>
                                <span class="fs-14">งานเลขา</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">25</h2>
                                <span class="fs-14">รองผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <h2 class="count">221</h2>
                                <span class="fs-14">ผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                                <h2 class="count">4</h2>
                                <span class="fs-14">เอกสารส่งแก้ไข</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade show " id="folder02" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row shapreter-row">
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-eye"></i>
                                </span>
                                <h2 class="count">94</h2>
                                <span class="fs-14">สารบรรณกลาง</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="far fa-comments"></i>
                                </span>
                                <h2 class="count">261</h2>
                                <span class="fs-14">หัวหน้ากลุ่มงานบริหาร</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">581</h2>
                                <span class="fs-14">งานเลขา</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">25</h2>
                                <span class="fs-14">รองผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <h2 class="count">221</h2>
                                <span class="fs-14">ผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                                <h2 class="count">4</h2>
                                <span class="fs-14">เอกสารส่งแก้ไข</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade show " id="folder03" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row shapreter-row">
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-eye"></i>
                                </span>
                                <h2 class="count">94</h2>
                                <span class="fs-14">สารบรรณกลาง</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="far fa-comments"></i>
                                </span>
                                <h2 class="count">261</h2>
                                <span class="fs-14">หัวหน้ากลุ่มงานบริหาร</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">581</h2>
                                <span class="fs-14">งานเลขา</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">25</h2>
                                <span class="fs-14">รองผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <h2 class="count">221</h2>
                                <span class="fs-14">ผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                                <h2 class="count">4</h2>
                                <span class="fs-14">เอกสารส่งแก้ไข</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <div class="tab-pane fade show " id="folder04" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <div class="row shapreter-row">
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-eye"></i>
                                </span>
                                <h2 class="count">94</h2>
                                <span class="fs-14">สารบรรณกลาง</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="far fa-comments"></i>
                                </span>
                                <h2 class="count">261</h2>
                                <span class="fs-14">หัวหน้ากลุ่มงานบริหาร</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">581</h2>
                                <span class="fs-14">งานเลขา</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-suitcase"></i>
                                </span>
                                <h2 class="count">25</h2>
                                <span class="fs-14">รองผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <h2 class="count">221</h2>
                                <span class="fs-14">ผู้อำนวยการ</span>
                            </div>
                        </div>
                        <div class="col-xl-2 col-lg-2 col-sm-4 col-6">
                            <div class="static-icon">
                                <span>
                                    <i class="fas fa-phone-alt"></i>
                                </span>
                                <h2 class="count">4</h2>
                                <span class="fs-14">เอกสารส่งแก้ไข</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
