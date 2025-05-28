<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
//use app\modules\edocument\components\Ccomponent as CC;
use app\components\Ccomponent;

$css = '.modal-xl {max-width: 90% !important;}';
$this->registerCss($css);
$this->title = 'ระบบบริหารตารางเวร';
$this->params['breadcrumbs'][] = $this->title;

//Pjax::begin(['id' => 'mainLeave', 'timeout' => false, 'enablePushState' => false]);
$url = Url::to(['operate']);
$yymm = @$_GET['yy'] . @$_GET['mm'];
?>

<div class="card mt-3">
    <div class="card-content m-2">
        <div class="row">
            <div class="col-md-12">
                <?= $this->render('_search', ['model' => $dataProvider]) ?>
                <?PHP
                IF (!empty(@$_GET['dep'])) {
                    ?>
                    <div class="card mt-3 m-2">
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe  class="embed-responsive-items" src="<?= yii\helpers\Url::to(['viewdocs', 'date_between_a' => @$_GET['date_between_a'], 'date_between_b' => @$_GET['date_between_b'], 'dep' => @$_GET['dep']]) ?>#view=FitW" type="application/pdf" /></iframe>
                        </div>
                    </div>
                    <?PHP
                } else {
                    ?>

                    <div class="jumbotron mt-3">
                        <h4 class="text-center">กรุณาเลือกช่วงเวลา เพื่อแสดงผลข้อมูล</h4>
                    </div>
                    <?PHP
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php //Pjax::end();   ?>
