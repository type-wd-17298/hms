<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\modules\office\models\PaperlessApproval $model */
$this->title = 'บันทึกการขออนุญาตไปราชการ';
$this->params['breadcrumbs'][] = ['label' => 'Paperless Approvals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="paperless-approval-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
