<?php ?>
<div class="btn btn-danger mb-2 btn-block" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
    <div class="h1 text-white">กรุณาเสียบบัตรประชาชนของท่านค่ะ</div>
</div>
<div class="card">
    <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-label="Slide 1" aria-current="true"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class=""></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <?= yii\helpers\Html::img('@web/img/LABEL-KIOSK.png', ['class' => 'd-block w-100']) ?>
            </div>
            <div class="carousel-item">
                <?= yii\helpers\Html::img('@web/img/LABEL-KIOSK2.png', ['class' => 'd-block w-100']) ?>
            </div>
            <div class="carousel-item">
                <?= yii\helpers\Html::img('@web/img/LABEL-KIOSK3.png', ['class' => 'd-block w-100']) ?>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <?PHP # yii\helpers\Html::img('@web/img/LABEL-KIOSK.png', ['class' => 'card-img-top']) ?>
    <div class="card-header">
        <h2 class="font-weight-bold">ให้บริการรูปแบบอัตโนมัติ</h2>
    </div>
    <div class="card-body">
        <p class="float-end"><i class="fa-solid fa-check text-success"></i></p>
        <p class="card-text">1.ตรวจสอบสิทธิการมารับบริการ</p>
        <p class="float-end"><i class="fa-solid fa-check text-success"></i></p>
        <p class="card-text">2.Authentication code สปสช.</p>
        <p class="float-end"><i class="fa-solid fa-check text-success"></i></p>
        <p class="card-text">3.ลงทะเบียนเข้ารับบริการ</p>
        <hr>
        <p class="float-end font-weight-bold">999</p>
        <p class="card-text">เลขที่บัตรคิวการเข้ารับบริการ</p>
        <p class="float-end font-weight-bold">ทันตกรรม</p>
        <p class="card-text">เข้ารับบริการที่แผนก</p>
    </div>
    <div class="card-footer">
<!--        <p class="card-text d-inline"> asdasd</p>
        <div class="btn btn-danger">
            ยกเลิก
        </div>-->
        <a href="javascript:void(0);" class="" data-bs-toggle="modal" data-bs-target="#chooseDeparment">
            <div class="btn btn-success btn-block" >
                <div class="h1 text-white">เลือกแผนกเข้ารับบริการ</div>
            </div>
        </a>
    </div>
</div>

<div class="modal fade" id="exampleModalCenter" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">ข้อมูลผู้มารับบริการ</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        ใบนำทางผู้ป่วยนอก
                    </div>
                    <div class="col-md-4">
                        <div style="font-size: 80px;" class="float-right" >999</div>
                    </div>
                    <div class="col-md-12">
                        <b>HN 00012542</b>
                    </div>
                    <div class="col-md-12">
                        <b>ชื่อนายศิลา กลั่นแกล้ว</b>อายุ 39 ปี
                        <br><b>สิทธิ</b> กรมบัญชีกลาง(ข้าราชการ)
                        <br>วันที่ 22 มิถุนายน 2566 เวลา 16:1
                        <br>Authen Code - 99999999
                        <br>Authen Date - วันที่ 22 มิถุนายน 2566 เวลา 16:12

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger light  btn-lg" data-bs-dismiss="modal">ยกเลิก</button>
                <button type="button" class="btn btn-success btn-lg">ยืนยันการเข้ารับบริการ</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="chooseDeparment" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">เลือกแผนกเข้ารับบริการ</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body">
                <div class="basic-list-group">
                    <div class="list-group">
                        <a href="javascript:void()" class="list-group-item list-group-item-action" data-bs-dismiss="modal">คลินิกทันตกรรม</a>
                        <a href="javascript:void()" class="list-group-item list-group-item-action" data-bs-dismiss="modal">คลินิกเบาหวาน</a>
                        <a href="javascript:void()" class="list-group-item list-group-item-action" data-bs-dismiss="modal">คลินิกความดันโลหิตสูง</a>
                        <a href="javascript:void()" class="list-group-item list-group-item-action" data-bs-dismiss="modal">คลินิกศัยกรรมกระดูก</a>
                        <a href="javascript:void()" class="list-group-item list-group-item-action" data-bs-dismiss="modal">คลินิกตา</a>
                        <a href="javascript:void()" class="list-group-item list-group-item-action" data-bs-dismiss="modal">งานแพทย์แผนไทย</a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success btn-lg btn-block" data-bs-dismiss="modal">ปิดหน้าจอ</button>
            </div>
        </div>
    </div>
</div>