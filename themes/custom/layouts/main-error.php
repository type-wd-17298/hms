<?php

use app\assets\CustomAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$page = CustomAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="keywords" content="" />
        <meta name="author" content="" />
        <meta name="robots" content="" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="format-detection" content="telephone=no">
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
        <style>
            * {
                font-family: 'Prompt', sans-serif;
                font-family: 'Kanit', sans-serif;
            }
        </style>
    </head>
    <body class="vh-100">
        <?php $this->beginBody() ?>
        <div class="authincation h-100">
            <div class="container h-100">
                <div class="row justify-content-center h-100 align-items-center">
                    <div class="col-md-5">
                        <div class="form-input-content text-center error-page">
                            <h4><i class="fa fa-times-circle text-danger"></i> <?= Html::encode($this->title) ?></h4>
                            <p><?= $content ?></p>
                            <div>
                                <a class="btn btn-primary" href="<?= \Yii::$app->homeUrl ?>">กลับหน้าจอหลัก</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--        <div class="authincation h-100">
                    <div class="container h-100">
        <?PHP $content ?>
                    </div>
                </div>-->
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
