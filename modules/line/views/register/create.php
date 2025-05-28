<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\line\models\StaffRegister */

$this->title = 'Create Staff Register';
$this->params['breadcrumbs'][] = ['label' => 'Staff Registers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="staff-register-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
