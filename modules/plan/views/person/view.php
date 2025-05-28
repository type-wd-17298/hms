<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\Person */

$this->title = $model->person_id;
$this->params['breadcrumbs'][] = ['label' => 'People', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="person-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'person_id' => $model->person_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'person_id' => $model->person_id], [
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
            'person_id',
            'person_code',
            'person_cid',
            'person_status_id',
            'person_occupation_id',
            'person_education_id',
            'person_religion_id',
            'person_type_id',
            'person_sex',
            'person_fullname',
            'person_birthdate',
            'person_tel',
            'person_age',
            'person_line',
            'person_address_no',
            'person_address_moo',
            'person_address_code',
            'person_gps_lng',
            'person_gps_lat',
            'person_income',
            'person_chronic',
            'user_id',
            'department_code',
        ],
    ]) ?>

</div>
