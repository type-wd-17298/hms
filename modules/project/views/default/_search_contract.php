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
                'id' => 'frmIndex2',
                //'action' => ['index'],
                'method' => 'get',
                'options' => [
                    'data-pjax' => 1,
                    'class' => 'form-inline'
                ],
    ]);
    ?>

    <div class="input-group  mr-2">
        <input type="text" class="form-control" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
        echo Html::dropDownList('reptype', (isset($_GET['reptype']) ? $_GET['reptype'] : ''), ArrayHelper::map(ProjectType::find()->orderBy(['project_type_id' => 'ASC'])->asArray()->all(), 'project_type_id', 'project_type_name'),
                [
                    'class' => 'form-control d-none d-xl-block',
                    'prompt' => '---เลือกประเภททั้งหมด---',
        ]);
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> ค้นหา', ['class' => 'btn btn-primary active']) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
