<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\PersonScreen */

$this->title = 'แบบสำรวจข้อมูลสุขภาพ';
$this->params['breadcrumbs'][] = ['label' => 'Person Screens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-screen-create">

    <h4><?= Html::encode($this->title) ?></h4>

    <?=
    $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ])
    ?>

</div>
