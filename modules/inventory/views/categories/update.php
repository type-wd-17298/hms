<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\ItemsCategories */

$this->title = 'Update Items Categories: ' . $model->categories_id;
$this->params['breadcrumbs'][] = ['label' => 'Items Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->categories_id, 'url' => ['view', 'id' => $model->categories_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="items-categories-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
