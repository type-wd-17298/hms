<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\DepActivity */

$this->title = 'เพิ่มข้อมูลกิจกรรมสุขภาพ';
$this->params['breadcrumbs'][] = ['label' => 'Dep Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="dep-activity-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?=
    $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig
    ])
    ?>

</div>
