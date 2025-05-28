<?php
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap4\Html;
use yii\widgets\ActiveForm;

/*
 * @var $this  yii\web\View
 * @var $form  yii\widgets\ActiveForm
 * @var $model dektrium\user\models\SettingsForm
 */

$this->title = Yii::t('user', 'Account settings');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= $this->render('/_alert', ['module' => Yii::$app->getModule('user')]) ?>

<div class="row">
    <div class="col-md-3">
        <?= $this->render('_menu') ?>
    </div>
    <div class="col-md-9">
        <div class="card">
            <div class="card-header bg-primary text-white h4">
                <?= Html::encode($this->title) ?>
            </div>
            <div class="card-body">
                <?php
                $form = ActiveForm::begin([
                            'id' => 'account-form',
                            //'options' => ['class' => 'form-horizontal'],
//                            'fieldConfig' => [
//                                'template' => "{label}\n<div class=\"col-lg-9\">{input}</div>\n<div class=\"col-sm-offset-3 col-lg-9\">{error}\n{hint}</div>",
//                                'labelOptions' => ['class' => 'col-lg-3 control-label'],
//                            ],
                            'enableAjaxValidation' => true,
                            'enableClientValidation' => false,
                ]);
                ?>
                <div class="mb-3">
                    <?= $form->field($model, 'email') ?>
                </div>
                <div class="mb-3">
                    <?= $form->field($model, 'username') ?>
                </div>
                <div class="mb-3">
                    <?= $form->field($model, 'new_password')->passwordInput() ?>
                </div>
                <hr />
                <div class="mb-3">
                    <?= $form->field($model, 'current_password')->passwordInput() ?>
                </div>
                <div class="form-group">
                    <?= Html::submitButton(Yii::t('user', 'Save'), ['class' => 'btn btn-block btn-primary btn-lg']) ?><br>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
