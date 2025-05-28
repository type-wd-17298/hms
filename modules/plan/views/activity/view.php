<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\DepActivity */

$this->title = $model->dep_activity_id;
$this->params['breadcrumbs'][] = ['label' => 'Dep Activities', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="dep-activity-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'dep_activity_id' => $model->dep_activity_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'dep_activity_id' => $model->dep_activity_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'dep_activity_id',
            'dep_activity_title',
            'dep_activity_summary',
            'dep_activity_purpose',
            'dep_activity_date',
            'dep_activity_videoclip',
            'dep_activity_photo',
            'who_create',
            'department_code',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
