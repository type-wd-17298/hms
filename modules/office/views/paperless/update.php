<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\DepActivity */

$this->title = 'แก้ไขบันทึกข้อความ';
//$this->params['breadcrumbs'][] = ['label' => 'Dep Activities', 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->dep_activity_id, 'url' => ['view', 'dep_activity_id' => $model->dep_activity_id]];
//$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dep-activity-update">
    <?=
    $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig
    ])
    ?>

</div>
