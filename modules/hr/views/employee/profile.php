<?PHP

use kartik\helpers\Html;
use app\components\Cdata;
use app\components\Ccomponent;

$userProfile = Cdata::getDataUserAccount($emp->employee_cid);
//print_r($userProfile);
//$hosname = @Ccomponent::Emp($model->profile->cid)->dep->employee_dep_label;
?>
<div class="row">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header border-0 flex-wrap align-items-start">
                <div class="col-md-8">
                    <div class="user d-sm-flex d-block pe-md-5 pe-0">
                        <?= @Html::img($userProfile['pictureUrl'], ['class' => 'img-fluid img-thumbnail img-responsive float-left mr-1', 'width' => '60']) ?>
                        <div class="ms-sm-3 ms-0 me-md-5 md-0">
                            <h5 class="mb-1 font-w600"><a href="javascript:void(0);"><?= @$emp->employee_fullname ?></a></h5>
                            <div class="listline-wrapper mb-2">
                                <span class="item"><i class="far fa-envelope"></i><?= @$userProfile['email'] ?></span>
                                <span class="item"><i class="far fa-id-badge"></i><?= @$emp->position->employee_position_name ?></span>
                                <span class="item"><i class="fas fa-map-marker-alt"></i>Thailand</span>
                            </div>
                            <p></p>
                        </div>
                    </div>
                </div>

            </div>
            <div class="card-body pt-0">
                <h4 class="fs-20">Description</h4>
                <div class="row">
                    <div class="col-xl-6 col-md-6">
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">เลขบัตรประชาชน : </span><span class="font-w400"><?= @Ccomponent::FnID($emp->employee_cid) ?></p>
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">ชื่อ-นามสกุล : </span><span class="font-w400"><?= @$emp->employee_fullname ?></span></p>
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">ตำแหน่ง : </span><span class="font-w400"><?= @$emp->position->employee_position_name ?></span></p>
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">หน่วยงาน : </span><span class="font-w400"><?= @$emp->dep->employee_dep_label ?></span></p>

                        <!--
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Current Designation : </span><span class="font-w400">PHP Programmer</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Annual Salary : </span><span class="font-w400">$7.5Lacs</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Current Company : </span><span class="font-w400">Abcd pvt Ltd</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Experience : </span><span class="font-w400">3 Yrs</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Location :</span> <span class="font-w400">USA</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Preferred Location : </span><span class="font-w400">USA</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Qualification: </span><span class="font-w400">B.Tech(CSE)</span></p>
                                                <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Key Skills: </span><span class="font-w400">Good Communication, Planning and research skills</span></p>
                        -->
                    </div>
                    <div class="col-xl-6 col-md-6">
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">Email :</span> <span class="font-w400"><?= @$userProfile['email'] ?></span></p>
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">เบอร์โทรศัพท์ : </span><span class="font-w400"><?= @Ccomponent::FnMobile($emp->employee_phone) ?></span></p>
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">วันเดือนปีเกิด : </span><span class="font-w400"><?= @Ccomponent::getThaiDate($emp->employee_birthdate) ?></span></p>
                        <p class="font-w600 mb-2 d-flex"><span class="custom-label-2">ที่อยู่ :</span> <span class="font-w400"><?= @$emp->employee_address ?></span></p>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex flex-wrap justify-content-between">
                <div class="mb-md-2 mb-3">
                    <span class="d-block mb-1"><i class="fas fa-circle me-2"></i>Currently Working at  <strong><?= @$emp->dep->employee_dep_label ?></strong></span>
                    <span><i class="fas fa-circle me-2"></i>3 Yrs Of Working Experience in   <strong>-</strong></span>
                </div>
                <div>
                    <a href="javascript:void(0);" class="btn btn-primary btn-md me-2  mb-2"><i class="fas fa-download me-2"></i>Download Ruseme</a>
                </div>
            </div>
        </div>
    </div>
</div>