<div class="alert alert-light notification">
    <p class="notificaiton-title mb-2"><strong>รายละเอียด</strong>
    </p>
    <p><?= @$model->paperless_detail ?></p>

    <!--  <button class="btn btn-dark btn-sm">Confirm</button>
     <button class="btn btn-link btn-sm">Cancel</button>-->
</div>
<div class="pt">
    <div id="embedContent">
        <div class="embed-responsive embed-responsive-16by9">
            <iframe class="embed-responsive-item" src="<?= yii\helpers\Url::to(['view', 'id' => $pid]) ?>#view=FitW" allowfullscreen></iframe>
        </div>
    </div>
</div>


