<?php

use yii\helpers\Html;

$this->title = 'แก้ไขการขออนุญาตไปราชการ: ' . $model->approval_id;
$this->params['breadcrumbs'][] = ['label' => 'Paperless Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->approval_id, 'url' => ['view', 'approval_id' => $model->approval_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="paperless-approval-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
