<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\edocument\models\LeaveMain */

$this->title = 'Update Leave Main: ' . $model->leave_id;
$this->params['breadcrumbs'][] = ['label' => 'Leave Mains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->leave_id, 'url' => ['view', 'id' => $model->leave_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="leave-main-update">


    <?=
    $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig
    ])
    ?>

</div>
