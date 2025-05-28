<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
use app\components\Ccomponent;
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

if (!isset($_GET['dep'])) {
    $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
    $_GET['dep'] = $emp->employee_dep_id;
}
?>
<form id="frmSearch" class="form-inline1"  action="<?= Url::to(['index', 'attr' => @$attr, 'view' => @$_GET['view']]) ?>">
    <div class="input-group input-group-lg1">
        <input type="text" class="form-control form-control search-area" name="qsearchManage" value="<?= @$_GET['qsearchManage'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
        echo Html::dropDownList('dep', @$_GET['dep'], ArrayHelper::map(app\modules\hr\models\EmployeeDep::find()->where(['employee_dep_status' => 1])->joinWith('type')->orderBy(['category_id' => 'ASC'])->asArray()->all(), 'employee_dep_id', 'employee_dep_label', 'type.category_name'),
                [
                    'class' => 'form-control form-control-md ', //d-none d-xl-block
                    'prompt' => '---เลือกหน่วยงานทั้งหมด---',
                    'width' => '10',
        ]);
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fas fa-search fa-lg"></i> แสดงข้อมูล', ['class' => 'btn btn-primary']) ?>
            <?PHP //echo Html::button('<i class="fa-solid fa-file-circle-plus fa-lg"></i> เพิ่มคำขอการลา', ['class' => 'btn btn-primary btnCreateLink ']) ?>
        </div>
    </div>
</form>

<?php
//ActiveForm::end(); ?>