<?php

use app\assets\CustomAsset;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$page = CustomAsset::register($this);
$theme = [
    'layout' => 'vertical', //horizontal vertical
    'primary' => 'color_14',
    'headerBg' => 'color_9',
    'navheaderBg' => 'color_9',
    'sidebarBg' => 'color_1',
    'sidebarStyle' => 'full', //["full" , "mini" , "compact" , "modern" , "overlay" , "icon-hover"]
];
$js = <<<JS
    dlabSettingsOptions = {
        typography: "roboto",
        version: "light", //dark light
        layout: "{$theme['layout']}", //horizontal   vertical
        primary: "{$theme['primary']}",
        headerBg: "{$theme['headerBg']}",
        navheaderBg: "{$theme['navheaderBg']}",
        sidebarBg: "{$theme['sidebarBg']}",
        sidebarStyle: "{$theme['sidebarStyle']}",
        sidebarPosition: "fixed",
        headerPosition: "fixed",
        containerLayout: "full",
    };
    new dlabSettings(dlabSettingsOptions);
JS;
$this->registerJs($js, $this::POS_READY);
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
        <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,700,700i,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Kanit:400,700,900&amp;display=swap&amp;subset=thai" rel="stylesheet">
        <?php $this->head() ?>
        <style>
            * {
                font-family: 'Prompt', sans-serif;
                font-family: 'Kanit', sans-serif;

            }
            .bg-authentication {
                background-color: #EFF2F7;
            }
            html body.bg-full-screen-image {
                background: url(<?= yii\helpers\Url::to('@web/img/vuexy-login-bg.jpg') ?>) no-repeat center center;
                background-size: cover;
            }
        </style>
    </head>
    <body class="bg-full-screen-image">
        <?php $this->beginBody() ?>
        <div class="authincation">
            <div class="container">
                <?= $content ?>
            </div>
        </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>
