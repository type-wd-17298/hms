<?php

use dektrium\user\models\UserSearch;
use yii\data\ActiveDataProvider;
use kartik\grid\GridView;
use yii\helpers\Html;
#use yii\jui\DatePicker;
use yii\web\View;
#use yii\widgets\Pjax;
use app\components\CadminManager;
#use app\models\Chospital;
use app\components\Cdata;
use app\components\Ccomponent;

/**
 * @var View $this
 * @var ActiveDataProvider $dataProvider
 * @var UserSearch $searchModel
 */
$this->title = Yii::t('user', 'Manage users');
$this->params['breadcrumbs'][] = $this->title;
?>

<?=

$this->render('/_alert', [
    'module' => Yii::$app->getModule('user'),
])
?>

<?= $this->render('/admin/_menu') ?>

<?php #Pjax::begin() ?>
<?=

GridView::widget([
    'export' => false,
    'pjax' => true,
    #'floatHeader' => true,
    'panel' => [
        'heading' => $this->title,
        'type' => '',
        //'before' => Html::a('<i class="fa-solid fa-plus"></i> เพิ่มรายการ', ['create'], ['class' => 'btn btn-success']),
        'before' => $this->render('_search', ['model' => $dataProvider]),
    ],
    'responsiveWrap' => FALSE,
    'striped' => FALSE,
    'bordered' => FALSE,
    //'hover' => TRUE,
    // 'condensed' => FALSE,
    'dataProvider' => $dataProvider,
    //'filterModel' => $searchModel,
    //'layout' => "{items}\n{pager}",
    'columns' => [
        [
            'label' => 'หน่วยงาน',
            'attribute' => 'depcode',
            'value' => function ($model) {
                $hosname = @Ccomponent::Emp($model->profile->cid)->dep->employee_dep_label;
                return $hosname;
            },
            'format' => 'raw',
        //'groupedRow' => true,
        //'group' => true,
        ],
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'label' => 'ชื่อ-นามสกุล',
            'attribute' => 'depcode',
            'noWrap' => TRUE,
            'value' => function ($model) {
                $userProfile = Cdata::getDataUserAccount($model->id);
                $hosname = @Ccomponent::Emp($model->profile->cid)->dep->employee_dep_label;
                return @Html::img($userProfile['pictureUrl'], ['class' => 'img-fluid img-thumbnail img-responsive float-left mr-1', 'width' => '60']) .
                '<b>' . @$model->profile->name . ' ' . @$model->profile->lname . '</b> [' . Ccomponent::FnIDX($model->profile->cid) . ']<br>' . $hosname;
            },
            'format' => 'raw',
        ],
        [
            'label' => 'ชื่อผู้ใช้งาน',
            'attribute' => 'username',
            'noWrap' => TRUE,
            'value' => function ($model) {
                return $model->username;
            },
            'format' => 'raw',
        ],
        [
            'label' => 'สิทธิใช้งาน',
            #'attribute' => 'profile.depcode',
            'value' => function ($model) {
                //return CadminManager::getAuthUser($model->id);
                return CadminManager::getAuthUser($model->id);
            },
            'visible' => 1,
            'format' => 'raw',
        ],
        #'email:email',
        [
            'attribute' => 'registration_ip',
            'value' => function ($model) {
                return $model->registration_ip == null ? '<span class="not-set">' . Yii::t('user', '(not set)') . '</span>' : $model->registration_ip;
            },
            'format' => 'html',
            'visible' => 0,
        ],
        [
            'attribute' => 'created_at',
            'value' => function ($model) {
                return @Ccomponent::getThaiDate(date('Y-m-d H:i:s', $model->created_at), 'S', 1);
            },
        ],
        [
            'header' => 'เข้าใช้ล่าสุด',
            'attribute' => 'last_login_at',
            'value' => function ($model) {
                if ($model->last_login_at) {
                    return @Ccomponent::getThaiDate(date('Y-m-d H:i:s', $model->last_login_at), 'S', 1);
                } else {
                    return '-';
                }
            },
        ],
        [
            'header' => Yii::t('user', 'ยื่นยันใช้งาน'),
            'visible' => 0,
            'value' => function ($model) {
                if ($model->isConfirmed) {
                    return '<div class="text-center"><span class="text-success">' . Yii::t('user', 'Confirmed') . '</span></div>';
                } else {
                    return Html::a(Yii::t('user', 'Confirm'), ['confirm', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-success btn-block',
                                'data-method' => 'post',
                                'data-pjax' => '1',
                                    #'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
        //'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
        [
            'header' => Yii::t('user', 'ยื่นยันสิทธิ'),
            'vAlign' => 'middle',
            'value' => function ($model) {
                $u = CadminManager::getAuthUser($model->id);
                if (!empty($u)) {
                    return '<div class="text-center"><span class="text-success">ยื่นยันสิทธิแล้ว</span></div>';
                } else {
                    return Html::a('ยื่นยันสิทธิ', ['assignuser', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-warning btn-block',
                                'data-method' => 'post',
                                    //'data-pjax' => '1',
                                    #'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
                    ]);
                }
            },
            'format' => 'raw',
            'visible' => Yii::$app->getModule('user')->enableConfirmation,
        ],
//        [
//            'header' => Yii::t('user', 'ยื่นยันสิทธิ IC'),
//            'value' => function ($model) {
//                $u = CadminManager::getAuthUserIC($model->id);
//                if (!empty($u)) {
//                    return '<div class="text-center"><span class="text-success">ยื่นยัน IC แล้ว</span></div>';
//                } else {
//                    return Html::a('ยื่นยันสิทธิ', ['assignuseric', 'id' => $model->id], [
//                                'class' => 'btn btn-xs btn-warning btn-block',
//                                'data-method' => 'post',
//                                    //'data-pjax' => '1',
//                                    #'data-confirm' => Yii::t('user', 'Are you sure you want to confirm this user?'),
//                    ]);
//                }
//            },
//            'format' => 'raw',
//            'visible' => Yii::$app->user->can('IC-manager'),
//        ],
        [
            'header' => 'สถานะ',
            'visible' => 1,
            'vAlign' => 'middle',
            'value' => function ($model) {
                if ($model->isBlocked) {
                    return Html::a(Yii::t('user', 'Unblock'), ['block', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-success btn-block btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to unblock this user?'),
                                'data-pjax' => '1',
                    ]);
                } else {
                    return Html::a(Yii::t('user', 'Block'), ['block', 'id' => $model->id], [
                                'class' => 'btn btn-xs btn-danger btn-block btn-sm',
                                'data-method' => 'post',
                                'data-confirm' => Yii::t('user', 'Are you sure you want to block this user?'),
                                'data-pjax' => '1',
                    ]);
                }
            },
            'format' => 'raw',
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
        ],
    ],
]);
?>

<?php #Pjax::end()    ?>
