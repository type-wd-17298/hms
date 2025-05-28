<?php

//use yii\helpers\Url;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
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

    <div class="input-group  mr-2">
        <div class="input-group-prepend d-none">
            <div class="btn d-none d-xl-block">{summary}</div>
        </div>
        <input type="text" class="form-control form-control-lg" id="inputSearch" name="search" value="<?= @$_GET['search'] ?>" placeholder="ค้นหารายการ" >
        <?PHP
//        //echo Html::hiddenInput('vcode', '', ['id' => 'vcode']);
//        //echo Html::hiddenInput('vmoo', '', ['id' => 'vmoo']);
//        Html::dropDownList('reptype', (isset($_GET['reptype']) ? $_GET['reptype'] : ''), ArrayHelper::map(app\modules\survay\models\PersonType::find()->orderBy(['person_type_id' => 'ASC'])->asArray()->all(), 'person_type_id', 'person_type_name'),
//                [
//                    'class' => 'form-control d-none d-xl-block',
//                    'prompt' => '---เลือกกลุ่มทั้งหมด---',
//        ]);
        ?>
        <?PHP
//        if (\Yii::$app->user->can('SuperAdmin')) {
//            echo Html::dropDownList('dep', (isset($_GET['dep']) ? $_GET['dep'] : ''), ArrayHelper::map(app\modules\survay\models\Cdepartment::find()->orderBy(['department_code' => 'ASC'])->asArray()->all(), 'department_code', 'department_name'),
//                    [
//                        'class' => 'form-control d-none d-xl-block',
//                        'prompt' => '---เลือกหน่วยงานทั้งหมด---',
//                        'width' => '10',
//            ]);
//        }
        ?>
        <div class="input-group-append">
            <?= Html::submitButton('<i class="fa-solid fa-magnifying-glass fa-lg"></i> ค้นหา', ['class' => 'btn btn-primary']) ?>
            <?= Html::a('<i class="fa-solid fa-repeat"></i> Reset การค้นหา', ['index'], ['class' => 'btn btn-dark']) ?>
            <?= Html::a('<i class="fa-solid fa-user-plus fa-lg"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-dark', 'data-pjax' => 0]) ?>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
