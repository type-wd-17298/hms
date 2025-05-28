<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<?php
/*
  $form = ActiveForm::begin([
  'id' => 'frmSearch',
  'action' => ['index'],
  'method' => 'get',
  'options' => [
  'data-pjax' => 1,
  //'class' => 'form-inline'
  ],
  ]);
 *
 */
$attr = @$_GET['attr'];
$js = <<<JS
        $('#frmSearch').on('submit', function (e) {
           e.preventDefault();
           var form = $(this);
            $.ajax({
                url: form.attr('action'),
                type: 'get',
                data: form.serialize(),
                success: function(response) {
                    $('{$attr}').html(response);
                }
            });
        return false;
    });

JS;
$this->registerJs($js, $this::POS_READY);
?>
<form id="frmSearch" class="form-inline1"  action="<?= Url::to(['index', 'attr' => @$attr, 'view' => @$_GET['view']]) ?>">
    <div class="input-group input-group-lg1">
        <input type="text" class="form-control form-control search-area" name="qsearchManage" value="<?= @$_GET['qsearchManage'] ?>" placeholder="ค้นหารายการ" >
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fas fa-search fa-lg"></i> แสดงข้อมูล', ['class' => 'btn btn-secondary active']) ?>
            <?PHP echo Html::button('<i class="fa-solid fa-file-circle-plus fa-lg"></i> เพิ่มคำขอการลา', ['class' => 'btn btn-primary btnCreateLink ']) ?>
        </div>
    </div>
</form>

<?php
//ActiveForm::end(); ?>