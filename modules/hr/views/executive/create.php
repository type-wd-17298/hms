<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\project\models\ProjectType $model */
$this->title = 'ประเภท';
$this->params['breadcrumbs'][] = ['label' => 'Project Types', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="project-type-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
