<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class CustomAsset extends AssetBundle {

    public $basePath = '@app/themes/custom/assets/xhtml';
    public $baseUrl = '@web/../themes/custom/assets/xhtml';
    public $css = [
        //'bootstrap.min.css',
        '//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css',
        'vendor/jquery-nice-select/css/nice-select.css',
        //'vendor/owl-carousel/owl.carousel.css',
        'vendor/sweetalert2/dist/sweetalert2.min.css',
        'vendor/jquery-smartwizard/dist/css/smart_wizard.min.css',
        'css/style.css',
        "../custom.css",
    ];
    public $js = [
        '//cdnjs.cloudflare.com/ajax/libs/paho-mqtt/1.0.1/mqttws31.min.js',
        '//code.jquery.com/ui/1.13.2/jquery-ui.js',
        //'//static.line-scdn.net/liff/edge/2/sdk.js'
        //'//static.line-scdn.net/liff/edge/versions/2.19.0/sdk.js',
        //<!-- Required vendors -->
        "vendor/global/global.min.js",
        "vendor/jquery-nice-select/js/jquery.nice-select.min.js",
        "js/custom.min.js",
        //'vendor/sweetalert2/dist/sweetalert2.min.js',
        '//cdn.jsdelivr.net/npm/sweetalert2@11',
        'vendor/nestable2/js/jquery.nestable.min.js',
        //'js/plugins-init/sweetalert.init.js',
        //"js/dlabnav-init.js",
        //"js/styleSwitcher.js",
        //'vendor/fullcalendar/js/main.min.js',
        //'js/plugins-init/fullcalendar-init.js',
        "../js/custom.js",
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap4\BootstrapAsset',
    ];

}
