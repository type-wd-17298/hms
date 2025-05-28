<div class="default-tab">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i>หนังสือราชการ</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="pt-4">
                <div id="embedContent">
                    <div class="embed-responsive embed-responsive-1by1">
                        <iframe class="embed-responsive-item" src="<?= yii\helpers\Url::to(['view', 'id' => $model->paperless_id]) ?>#view=FitH" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
