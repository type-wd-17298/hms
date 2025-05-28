<?php

use yii\helpers\Url;

$url = Url::to(['calendar']);
$urlBook = Url::to(['create']);
$js = <<<JS
$(".bookingRoom").click(function(event){
        $('#modalRoom').modal('show');
         $.get("{$urlBook}",{id:$(this).data('id')}, function(data) {
         $("#modalRoomContents").html(data);
    });
});
    $.get("{$url}",{}, function(data) {
         $("#calendar-body").html(data);
    });
JS;
$this->registerJs($js, $this::POS_READY);
?>
<h3 class="text-primary font-weight-bolder">
    <i class="fas fa-solid fa-file-signature"></i> ระบบจองห้องประชุม
</h3>
<div class="row">
    <div class="col-xl-3 col-xxl-4">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-intro-title">รายการห้องประชุม</h4>
                        <div class="">
                            <div id="external-events" class="my-3">
                                <div class="profile-news">
                                    <?PHP
                                    foreach ($data as $row) {
                                        ?>
                                        <div class="pt-1 pb-1 pl-1 pr-1 external-event btn bookingRoom" data-id="<?= $row->bk_meetingroom_id ?>" style="background-color:<?= $row->bk_meetingroom_color ?>;">
                                            <img src="https://spaces.imgix.net/mediaFiles/ZS82LzUvZS9lNjVlZjNlMTBiYWFkNGRmMTE2ZjAwNjk1ODYxZDk1OTgwOWFmNTY5X1NwYWNlc19DaGFtY2h1cmlfU3F1YXJlX0Jhbmdrb2tfNDgxN19tZWV0aW5nX3Jvb20uanBnL2Rvd25sb2Fk?auto=compress,format&q=30" alt="image" class="me-3 rounded img-thumbnail" width="75">
                                            <div class="media-body" >
                                                <h5 class="m-b-5"><a href="javascript:;" class="text-white"><?= $row->bk_meetingroom_name ?></a></h5>
                                                <p class="mb-0 font-weight-bold"><i class="fa-solid fa-arrow-right"></i> จองห้องประชุม</p>
                                            </div>
                                        </div>
                                        <?PHP
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body">
                        <div id="calendar2-body"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-9 col-xxl-8">
        <div class="card">
            <div class="card-body">
                <div id="calendar-body"></div>
            </div>
        </div>
    </div>

    <!-- Modal Add Category -->
    <div class="modal fade  bg-success-light" id="modalEvent"  aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><strong>รายการการใช้งานห้องประชุม</strong></h3>
                </div>
                <div class="modal-body1 m-1" id="modalContents">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade  bg-success-light" id="modalRoom"  aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><strong>จองการใช้งานห้องประชุม</strong></h3>
                </div>
                <div class="modal-body" id="modalRoomContents"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger waves-effect waves-light" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
