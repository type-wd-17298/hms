<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\line\models\StaffRegister */

$this->title = 'Update Staff Register: ' . $model->user_id;
$this->params['breadcrumbs'][] = ['label' => 'Staff Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->user_id, 'url' => ['view', 'id' => $model->user_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="staff-register-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
