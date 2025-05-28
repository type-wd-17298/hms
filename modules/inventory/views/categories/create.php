<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\modules\inventories\models\ItemsCategories */

$this->title = 'Create Items Categories';
$this->params['breadcrumbs'][] = ['label' => 'Items Categories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="items-categories-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
