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
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Kanit:400,700,900&amp;display=swap&amp;subset=thai" rel="stylesheet">
        <style>
            * {
                font-family: 'Prompt', sans-serif;
                font-family: 'Kanit', sans-serif;
                font-size: 36px;
            }
            /*
            body {
                background-image: url("https://wallpaperaccess.com/full/2082226.jpg");
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;
            }
            */
        </style>
    </head>
    <body >
        <?php $this->beginBody() ?>
        <div id="preloader">
            <div class="lds-ripple">
                <div></div>
                <div></div>
            </div>
        </div>
        <div class="mt-5">
            <div class="container-fluid">
                <?= $content ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
