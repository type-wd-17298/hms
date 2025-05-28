<?php

use yii\helpers\Url;
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;

if (strlen($id) == 13) {
    $js = <<<JS
             $('[href="#profile"]').tab('show');
            //window.print();
      JS;
    $this->registerJs($js, $this::POS_READY);
} else {
    $defaultTab = '';
}
$url = Url::to(['employee/addexecutive']);
?>
<div class="h2"><?= $emp->employee_fullname ?></div>
<!-- Nav tabs -->
<div class="default-tab" id="tabEmp">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link font-weight-bold active" data-bs-toggle="tab" href="#home"><i class="la la-keyboard-o me-2"></i> ข้อมูลทั่วไป</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab" href="#executive"><i class="la la-television me-2"></i> ข้อมูลตำแหน่งบริหาร</a>
        </li>
        <li class="nav-item">
            <a class="nav-link font-weight-bold" data-bs-toggle="tab"  href="#profile"><i class="la la-user me-2"></i> แก้ไขข้อมูลส่วนตัว</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade show active" id="home" role="tabpanel">
            <div class="">
                <?PHP
                Pjax::begin(['id' => 'empForm', 'timeout' => false, 'enablePushState' => false,]); //
                ?>
                <?PHP
                echo $this->render('/employee/profile', [
                    'model' => $model,
                    'emp' => $emp,
                    'list' => $list,
                    'dep' => $dep,
                ])
                ?>
                <?php Pjax::end(); ?>
            </div>
        </div>
        <div class="tab-pane fade" id="executive">
            <?PHP
            Pjax::begin(['id' => 'executiveForm', 'timeout' => false, 'enablePushState' => false, 'options' => ['data-pjax-container' => 'executiveForm']]); //
            ?>
            <?=
            $this->render('_form_executive', [
                'model' => $model,
                'list' => $list,
                'dep' => $dep,
            ])
            ?>
            <?PHP
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'panelBeforeTemplate' => '{before}',
                'panelTemplate' => '<div class="">
  {panelBefore}
  {items}
  {panelAfter}
  {panelFooter}
  <div class="text-center m-2">{summary}</div>
  <div class="text-center m-2">{pager}</div>
  <div class="clearfix"></div>
  </div>',
                'responsiveWrap' => FALSE,
                'striped' => TRUE,
                'hover' => TRUE,
                'bordered' => FALSE,
                'condensed' => TRUE,
                //'pjax' => TRUE,
                'export' => FALSE,
                'toggleDataContainer' => ['class' => 'btn-group mr-2'],
                'panel' => [
                    'heading' => '',
                    'type' => 'primary',
                    'before' => '',
                ],
                'columns' => [
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'ชื่อตำแหน่งบริหาร',
                        'attribute' => 'executive.employee_executive_name',
                        'format' => 'raw',
                        'noWrap' => TRUE,
                        'visible' => 1,
                    ],
                    [
                        'headerOptions' => ['class' => 'font-weight-bold'],
                        'label' => 'ฝ่าย/กลุ่มงาน/กลุ่มภารกิจ',
                        'attribute' => 'dep.employee_dep_label',
                        'format' => 'raw',
                        'noWrap' => TRUE,
                        'visible' => 1,
                        'value' => function ($model) {
                            $html = '';
                            $checkDep = $model->getExecutiveDep();
                            foreach ($checkDep as $value) {
                                $html .= @Html::tag('div', $value->department->employee_dep_label, ['class' => '']);
                            }
                            return @$model->dep->employee_dep_label . $html;
                        }
                    ],
                    [
                        'visible' => 1,
                        'label' => 'ดำเนินการ',
                        'format' => 'raw',
                        'vAlign' => 'middle',
                        'noWrap' => TRUE,
                        'hAlign' => 'right',
                        'value' => function ($model) {
                            $html = '<div class="btn-group btn-group-toggle btn-group-xs">
                  <label class="btn btn-dark btn-xs"><i class="fas fa-remove fa-lg"></i>
                  ' . Html::a('ลบ', ['executive', 'id' => @$_GET['id'], 'did' => $model['employee_head_id']], [
                                        'class' => 'text-white text-decoration-none',
                                        'data' => [
                                            'pjax' => 1,
                                            'confirm' => 'คุณต้องการลบข้อมูลนี้หรือไม่?',
                                            'method' => 'post',
                                        ],
                                    ]) . '
                  </label>
                  </div>';
                            return $html;
                        }
                    ],
                ],
            ]);
            ?>

            <?php Pjax::end(); ?>
        </div>
        <div class="tab-pane fade mb-2" id="profile">
            <?PHP
            Pjax::begin(['id' => 'profileForm', 'timeout' => false, 'enablePushState' => false,]); // 'options' => ['data-pjax-container' => 'profileForm']
            ?>
            <?=
            $this->render('_form_profile', [
                'model' => $emp,
                'list' => $list,
                'dep' => $dep,
                'type' => $type,
                'position' => $position,
            ])
            ?>
            <?php Pjax::end(); ?>
        </div>
    </div>
</div>

