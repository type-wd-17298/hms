<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\edocument\models\LeaveMain */

//$this->title = 'Create Leave Main';
$this->params['breadcrumbs'][] = ['label' => 'Leave Mains', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="leave-main-create">

    <?=
    $this->render('_form', [
        'model' => $model,
        'initialPreview' => $initialPreview,
        'initialPreviewConfig' => $initialPreviewConfig
    ])
    ?>

</div>
