<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\edocument\models\LeaveMain */

$this->title = 'Update Leave Main: ' . $model->work_grid_change_id;
$this->params['breadcrumbs'][] = ['label' => 'Leave Mains', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->work_grid_change_id, 'url' => ['view', 'id' => $model->work_grid_change_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="leave-main-update">


    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
