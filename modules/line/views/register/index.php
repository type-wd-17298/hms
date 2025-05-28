<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ทะเบียนผู้ใช้งาน SPO CONNECT';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="card mt-3">
    <div class="card-content m-2">
        <h3>ทะเบียนผู้ใช้งาน SPO CONNECT</h3>
        <?php Pjax::begin(); ?>
        <p>
            <?= Html::a('<span class="fas fa-comment-alt" aria-hidden="true"></span> ส่งข้อความ', ['create'], ['class' => 'btn btn-success']) ?>
        </p>

        <?=
        GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'kartik\grid\SerialColumn'],
                [
                    'label' => 'รูปภาพ',
                    'format' => 'raw',
                    'width' => '2%',
                    'value' => function ($data) {
                        return \yii\bootstrap4\Html::img($data->user_data, ['class' => 'img-thumbnail', 'height' => '30']);
                    }
                ],
                [
                    'label' => 'ข้อมูลผู้ใช้งาน',
                    'format' => 'raw',
                    #'width' => '2%',
                    'value' => function ($data) {
                        return @$data->staff->profile->fullname;
                    }
                ],
                #'user_id',
                #'user_data:ntext',
                'date_create',
                'user_event',
                'user_active',
                ['class' => 'kartik\grid\ActionColumn'],
            ],
        ]);
        ?>
        <?php Pjax::end(); ?>
    </div>
</div>
