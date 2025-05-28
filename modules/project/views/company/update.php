<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\project\models\ProjectType $model */
$this->title = 'ประเภท : ' . $model->project_type_id;
$this->params['breadcrumbs'][] = ['label' => 'Project Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->project_type_id, 'url' => ['view', 'project_type_id' => $model->project_type_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="project-type-update">
    <h1><?= Html::encode($this->title) ?></h1>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
