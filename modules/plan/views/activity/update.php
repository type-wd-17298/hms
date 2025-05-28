<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\DepActivity */

$this->title = 'แก้ไขข้อมูลกิจกรรมสุขภาพ';
$this->params['breadcrumbs'][] = ['label' => 'Dep Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->dep_activity_id, 'url' => ['view', 'dep_activity_id' => $model->dep_activity_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="dep-activity-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?=
    $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig
    ])
    ?>

</div>
