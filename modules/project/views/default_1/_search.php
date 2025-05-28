<?php

use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\modules\project\models\ProjectType;
?>

<div class="person-search">

    <?php
    $form = ActiveForm::begin([
                'id' => 'frmIndex',
                'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                    'class' => 'form-inline'
                ],
    ]);
    ?>

    <div class="input-group input-group-md mr-2">
        <div class="input-group-prepend d-none">
            <div class="btn d-none d-xl-block">{summary}</div>
        </div>
        <input type="text" class="form-control" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
        echo Html::dropDownList('reptype', (isset($_GET['reptype']) ? $_GET['reptype'] : ''), ArrayHelper::map(ProjectType::find()->orderBy(['project_type_id' => 'ASC'])->asArray()->all(), 'project_type_id', 'project_type_name'),
                [
                    'class' => 'form-control d-none d-xl-block',
                    'prompt' => '---เลือกกลุ่มทั้งหมด---',
        ]);
        ?>

        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> ค้นหา', ['class' => 'btn btn-primary']) ?>
            <?PHP echo Html::a('<i class="fa-solid fa-file-circle-plus fa-lg"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-secondary']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
