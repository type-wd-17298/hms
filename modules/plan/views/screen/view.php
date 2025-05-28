<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\survay\models\PersonScreen */

$this->title = $model->person_screen_id;
$this->params['breadcrumbs'][] = ['label' => 'Person Screens', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="person-screen-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'person_screen_id' => $model->person_screen_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'person_screen_id' => $model->person_screen_id], [
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
            'person_screen_id',
            'person_id',
            'person_screen_date',
            'person_screen_weight',
            'person_screen_height',
            'person_screen_waist_cm',
            'person_screen_sbp',
            'person_screen_dbp',
            'person_screen_pulse',
            'person_screen_fbs',
            'person_screen_bmi',
            'person_screen_disability',
            'person_screen_hospcode',
            'person_screen_intype',
            'person_screen_eating',
            'person_screen_eating2',
            'person_screen_activity_body',
            'person_screen_activity_mind',
            'person_screen_q1',
            'person_screen_q2',
            'person_screen_vcc19',
            'person_screen_smoke',
            'person_screen_alcohol',
            'person_screen_income',
            'who_screen',
            'person_screen_result',
            'create_at',
            'update_at',
        ],
    ]) ?>

</div>
