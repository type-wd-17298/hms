<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\PersonScreen */

$this->title = 'แบบประเมินสุขภาพ';
$this->params['breadcrumbs'][] = ['label' => 'Person Screens', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->person_screen_id, 'url' => ['view', 'person_screen_id' => $model->person_screen_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="person-screen-update">

    <h4><?= Html::encode($this->title) ?></h4>

    <?=
    $this->render('_form', [
        'model' => $model,
        'data' => $data,
    ])
    ?>

</div>
