<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\Person */

$this->title = 'เพิ่มข้อมูลแผนปฏิบัติการ';
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="person-create">
    <h3><?= Html::encode($this->title) ?></h3>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
