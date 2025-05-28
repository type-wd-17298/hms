<?php

use app\assets\CustomAsset;
//use app\widgets\Alert;
//use yii\bootstrap4\Breadcrumbs;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use app\components\Cdata;
use app\components\Ccomponent;

if (in_array(Yii::$app->controller->action->id, ['error'])) {
    if (strlen(Yii::$app->user->identity->profile->cid) <> 13 || Yii::$app->user->identity->profile->name == '' || Yii::$app->user->identity->profile->lname == '') {
        Yii::$app->response->redirect(['/user/settings/profile']);
        Yii::$app->end();
    }
    echo $this->render(
            'main-error',
            ['content' => $content]
    );
} else {

    $id = uniqid();
    if (!isset($this->params['mqttFuncCheck']))
        $this->params['mqttFuncCheck'] = '';
    if (!isset($this->params['mqttSubTopics']))
        $this->params['mqttSubTopics'] = '';

    if (!Yii::$app->user->isGuest) {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $uid = $emp->employee_id;
        $userProfile = Cdata::getDataUserAccount();
        $userAuth = Cdata::getAuthUser(Yii::$app->user->id);
        $theme = [
            'layout' => 'vertical',
            'primary' => 'color_15',
            'headerBg' => '',
            'navheaderBg' => '',
            'sidebarBg' => '',
            'sidebarStyle' => 'full',
        ];

        if (\Yii::$app->user->can('OfficeAdmin')) {
            $theme = [
                'layout' => 'vertical',
                'primary' => 'color_10',
                'headerBg' => 'color_10',
                'navheaderBg' => 'color_10',
                'sidebarBg' => 'color_1',
                'sidebarStyle' => 'full',
            ];
        }
        if (\Yii::$app->user->can('SuperAdmin')) {
            $color = 'color_12';
            $cpk = 'color_15';
            $theme = [
                'layout' => Yii::$app->controller->module->id == 'inventory' ? 'vertical' : 'vertical', //horizontal vertical
                'primary' => $cpk,
                'headerBg' => '',
                'navheaderBg' => '1color_15',
                'sidebarBg' => '',
                'sidebarStyle' => Yii::$app->controller->module->id == 'inventory' ? 'mini' : 'full', //["full" , "mini" , "compact" , "modern" , "overlay" , "icon-hover"]
            ];
        }
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
    }
    $page = CustomAsset::register($this);
    $app = \Yii::$app;
    $MessageMqtt = '';
    $urlOper = Url::to(['/office/default/operation']);
    $js = <<<JS
var mqtt;
var reconnectTimeout = 3000;
function sub_topics(stopic){
	var soptions={qos:0};
	mqtt.subscribe(stopic,soptions);
	return false;
}

function onConnect(data){
    console.log("Connected");
    {$this->params['mqttSubTopics']}
    sub_topics('hms/service/operation/{$uid}');
}

function onFailure(e){
        console.log('Connection Attemp to Host '+'{$app->params['mqtt_host']}'+' Failed ');
        setTimeout(MQTTconnect,reconnectTimeout);
}
function onMessageArrived(msg){
        var topic = msg.destinationName;
        //var value = msg.payloadString;
        //out_msg += msg.payloadString;
        {$this->params['mqttFuncCheck']} //from Controller/view
        if(topic == 'hms/service/operation/{$uid}'){
            SetOperation();
        }
        console.log(topic);
        //console.log(value);
}

function MQTTconnect(){
    mqtt = new Paho.MQTT.Client('{$app->params['mqtt_host']}',{$app->params['mqtt_tls_port']},'mqttx_{$id}hms');
    var options = {
        timeout:5,
        onSuccess: onConnect,
        onFailure: onFailure,
        useSSL:true,
        userName: '{$app->params['mqtt_user']}',
        password: '{$app->params['mqtt_pass']}',
    };
    mqtt.onMessageArrived =  onMessageArrived
    mqtt.connect(options);
}
MQTTconnect();

function SetOperation(){
        $.get('{$urlOper}',{}, function(data) {
            $('#alertNotification01').html(data.cc_official+data.cc_paperless+data.cc_leave+data.cc_approval);
            $('#alertNotification02').html(data.cc_view);
            if(data.cc_official > 0){
                $('#cc_official').removeClass("d-none");
                $('#cc_official').html('+'+data.cc_official);
            }
         if(data.cc_paperless > 0){
                $('#cc_paperless').removeClass("d-none");
                $('#cc_paperless').html('+'+data.cc_paperless);
            }
         if(data.cc_leave > 0){
                $('#cc_leave').removeClass("d-none");
                $('#cc_leave').html('+'+data.cc_leave);
            }
         if(data.cc_view > 0){
                $('#cc_view').removeClass("d-none");
                $('#cc_view').html('+'+data.cc_view);
            }
         if(data.cc_approval > 0){
                $('#cc_approval').removeClass("d-none");
                $('#cc_approval').html('+'+data.cc_approval);
            }
        });
}
SetOperation();
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
            <link rel="icon" type="image/x-icon" href="<?= $page->baseUrl ?>/../logo-hms.png" />
            <?php $this->head() ?>
            <style>
                * {
                    font-family: 'Prompt', sans-serif;
                    font-family: 'Kanit', sans-serif;
                    font-size: <?= Yii::$app->params['fontSize'] ?>;
                }
                /*
                .table th, .table td {
                    padding: 0.5375rem 0.325rem;
                }
                */
            </style>
        </head>
        <body class="">
            <?php $this->beginBody() ?>
            <!--*******************
                   Preloader start
               ********************-->
            <div id="preloader">
                <div class="lds-ripple">
                    <div></div>
                    <div></div>
                </div>
            </div>
            <!--*******************
                Preloader end
            ********************-->
            <!--**********************************
                Main wrapper start
            ***********************************-->
            <div id="main-wrapper">

                <!--**********************************
                    Nav header start
                ***********************************-->
                <div class="nav-header">
                    <a href="<?= Url::home() ?>" class="brand-logo">
                        <?= Html::img($page->baseUrl . '/../logo-hms.png', ['class' => 'logo-abbr ml-1']) ?>
                        <span class="brand-title ml-2">HMS</span>
                    </a>
                    <div class="nav-control">
                        <div class="hamburger">
                            <span class="line"></span><span class="line"></span><span class="line"></span>
                        </div>
                    </div>
                </div>
                <!--**********************************
                  Nav header end
                ***********************************-->
                <!--**********************************
                  Chat box start
                  ***********************************-->
                <!--
                            <div class="chatbox ">
                                <div class="chatbox-close"></div>
                                <div class="custom-tab-1">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#notes">Notes</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#alerts">Alerts</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#chat">Chat</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div class="tab-pane fade active show" id="chat" role="tabpanel">
                                            <div class="card mb-sm-3 mb-md-0 contacts_card dlab-chat-user-box">
                                                <div class="card-header chat-list-header text-center">
                                                    <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/><rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/></g></svg></a>
                                                    <div>
                                                        <h6 class="mb-1">Chat List</h6>
                                                        <p class="mb-0">Show All</p>
                                                    </div>
                                                    <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg></a>
                                                </div>
                                                <div class="card-body contacts_body p-0 dlab-scroll  " id="DLAB_W_Contacts_Body">
                                                    <ul class="contacts">
                                                        <li class="name-first-letter">A</li>
                                                        <li class="active dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Archie Parker</span>
                                                                    <p>Kalid is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Alfie Mason</span>
                                                                    <p>Taherah left 7 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/3.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>AharlieKane</span>
                                                                    <p>Sami is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/4.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Athan Jacoby</span>
                                                                    <p>Nargis left 30 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="name-first-letter">B</li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/5.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Bashid Samim</span>
                                                                    <p>Rashid left 50 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Breddie Ronan</span>
                                                                    <p>Kalid is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Ceorge Carson</span>
                                                                    <p>Taherah left 7 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="name-first-letter">D</li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/3.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Darry Parker</span>
                                                                    <p>Sami is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/4.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Denry Hunter</span>
                                                                    <p>Nargis left 30 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="name-first-letter">J</li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/5.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Jack Ronan</span>
                                                                    <p>Rashid left 50 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Jacob Tucker</span>
                                                                    <p>Kalid is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>James Logan</span>
                                                                    <p>Taherah left 7 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/3.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Joshua Weston</span>
                                                                    <p>Sami is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="name-first-letter">O</li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/4.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Oliver Acker</span>
                                                                    <p>Nargis left 30 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="dlab-chat-user">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont">
                                                                    <img src="<?= $page->baseUrl ?>/images/avatar/5.jpg" class="rounded-circle user_img" alt=""/>
                                                                    <span class="online_icon offline"></span>
                                                                </div>
                                                                <div class="user_info">
                                                                    <span>Oscar Weston</span>
                                                                    <p>Rashid left 50 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="card chat dlab-chat-history-box d-none">
                                                <div class="card-header chat-list-header text-center">
                                                    <a href="javascript:void(0);" class="dlab-chat-history-back">
                                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24"/><rect fill="#000000" opacity="0.3" transform="translate(15.000000, 12.000000) scale(-1, 1) rotate(-90.000000) translate(-15.000000, -12.000000) " x="14" y="7" width="2" height="10" rx="1"/><path d="M3.7071045,15.7071045 C3.3165802,16.0976288 2.68341522,16.0976288 2.29289093,15.7071045 C1.90236664,15.3165802 1.90236664,14.6834152 2.29289093,14.2928909 L8.29289093,8.29289093 C8.67146987,7.914312 9.28105631,7.90106637 9.67572234,8.26284357 L15.6757223,13.7628436 C16.0828413,14.136036 16.1103443,14.7686034 15.7371519,15.1757223 C15.3639594,15.5828413 14.7313921,15.6103443 14.3242731,15.2371519 L9.03007346,10.3841355 L3.7071045,15.7071045 Z" fill="#000000" fill-rule="nonzero" transform="translate(9.000001, 11.999997) scale(-1, -1) rotate(90.000000) translate(-9.000001, -11.999997) "/></g></svg>
                                                    </a>
                                                    <div>
                                                        <h6 class="mb-1">Chat with Khelesh</h6>
                                                        <p class="mb-0 text-success">Online</p>
                                                    </div>
                                                    <div class="dropdown">
                                                        <a href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg></a>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li class="dropdown-item"><i class="fa fa-user-circle text-primary me-2"></i> View profile</li>
                                                            <li class="dropdown-item"><i class="fa fa-users text-primary me-2"></i> Add to btn-close friends</li>
                                                            <li class="dropdown-item"><i class="fa fa-plus text-primary me-2"></i> Add to group</li>
                                                            <li class="dropdown-item"><i class="fa fa-ban text-primary me-2"></i> Block</li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-body msg_card_body dlab-scroll" id="DLAB_W_Contacts_Body3">
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            Hi, how are you samim?
                                                            <span class="msg_time">8:40 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mb-4">
                                                        <div class="msg_cotainer_send">
                                                            Hi Khalid i am good tnx how about you?
                                                            <span class="msg_time_send">8:55 AM, Today</span>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            I am good too, thank you for your chat template
                                                            <span class="msg_time">9:00 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mb-4">
                                                        <div class="msg_cotainer_send">
                                                            You are welcome
                                                            <span class="msg_time_send">9:05 AM, Today</span>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            I am looking for your next templates
                                                            <span class="msg_time">9:07 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mb-4">
                                                        <div class="msg_cotainer_send">
                                                            Ok, thank you have a good day
                                                            <span class="msg_time_send">9:10 AM, Today</span>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            Bye, see you
                                                            <span class="msg_time">9:12 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            Hi, how are you samim?
                                                            <span class="msg_time">8:40 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mb-4">
                                                        <div class="msg_cotainer_send">
                                                            Hi Khalid i am good tnx how about you?
                                                            <span class="msg_time_send">8:55 AM, Today</span>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            I am good too, thank you for your chat template
                                                            <span class="msg_time">9:00 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mb-4">
                                                        <div class="msg_cotainer_send">
                                                            You are welcome
                                                            <span class="msg_time_send">9:05 AM, Today</span>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            I am looking for your next templates
                                                            <span class="msg_time">9:07 AM, Today</span>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-end mb-4">
                                                        <div class="msg_cotainer_send">
                                                            Ok, thank you have a good day
                                                            <span class="msg_time_send">9:10 AM, Today</span>
                                                        </div>
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/2.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                    </div>
                                                    <div class="d-flex justify-content-start mb-4">
                                                        <div class="img_cont_msg">
                                                            <img src="<?= $page->baseUrl ?>/images/avatar/1.jpg" class="rounded-circle user_img_msg" alt=""/>
                                                        </div>
                                                        <div class="msg_cotainer">
                                                            Bye, see you
                                                            <span class="msg_time">9:12 AM, Today</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-footer type_msg">
                                                    <div class="input-group">
                                                        <textarea class="form-control" placeholder="Type your message..."></textarea>
                                                        <div class="input-group-append">
                                                            <button type="button" class="btn btn-primary"><i class="fa fa-location-arrow"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="alerts" role="tabpanel">
                                            <div class="card mb-sm-3 mb-md-0 contacts_card">
                                                <div class="card-header chat-list-header text-center">
                                                    <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><circle fill="#000000" cx="5" cy="12" r="2"/><circle fill="#000000" cx="12" cy="12" r="2"/><circle fill="#000000" cx="19" cy="12" r="2"/></g></svg></a>
                                                    <div>
                                                        <h6 class="mb-1">Notications</h6>
                                                        <p class="mb-0">Show All</p>
                                                    </div>
                                                    <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/><path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/></g></svg></a>
                                                </div>
                                                <div class="card-body contacts_body p-0 dlab-scroll" id="DLAB_W_Contacts_Body1">
                                                    <ul class="contacts">
                                                        <li class="name-first-letter">SEVER STATUS</li>
                                                        <li class="active">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont primary">KK</div>
                                                                <div class="user_info">
                                                                    <span>David Nester Birthday</span>
                                                                    <p class="text-primary">Today</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="name-first-letter">SOCIAL</li>
                                                        <li>
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont success">RU</div>
                                                                <div class="user_info">
                                                                    <span>Perfection Simplified</span>
                                                                    <p>Jame Smith commented on your status</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li class="name-first-letter">SEVER STATUS</li>
                                                        <li>
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont primary">AU</div>
                                                                <div class="user_info">
                                                                    <span>AharlieKane</span>
                                                                    <p>Sami is online</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex bd-highlight">
                                                                <div class="img_cont info">MO</div>
                                                                <div class="user_info">
                                                                    <span>Athan Jacoby</span>
                                                                    <p>Nargis left 30 mins ago</p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="card-footer"></div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="notes">
                                            <div class="card mb-sm-3 mb-md-0 note_card">
                                                <div class="card-header chat-list-header text-center">
                                                    <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect fill="#000000" x="4" y="11" width="16" height="2" rx="1"/><rect fill="#000000" opacity="0.3" transform="translate(12.000000, 12.000000) rotate(-270.000000) translate(-12.000000, -12.000000) " x="4" y="11" width="16" height="2" rx="1"/></g></svg></a>
                                                    <div>
                                                        <h6 class="mb-1">Notes</h6>
                                                        <p class="mb-0">Add New Nots</p>
                                                    </div>
                                                    <a href="javascript:void(0);"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1"><g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><rect x="0" y="0" width="24" height="24"/><path d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z" fill="#000000" fill-rule="nonzero" opacity="0.3"/><path d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z" fill="#000000" fill-rule="nonzero"/></g></svg></a>
                                                </div>
                                                <div class="card-body contacts_body p-0 dlab-scroll" id="DLAB_W_Contacts_Body2">
                                                    <ul class="contacts">
                                                        <li class="active">
                                                            <div class="d-flex bd-highlight">
                                                                <div class="user_info">
                                                                    <span>New order placed..</span>
                                                                    <p>10 Aug 2020</p>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <a href="javascript:void(0);" class="btn btn-primary btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex bd-highlight">
                                                                <div class="user_info">
                                                                    <span>Youtube, a video-sharing website..</span>
                                                                    <p>10 Aug 2020</p>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <a href="javascript:void(0);" class="btn btn-primary btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex bd-highlight">
                                                                <div class="user_info">
                                                                    <span>john just buy your product..</span>
                                                                    <p>10 Aug 2020</p>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <a href="javascript:void(0);" class="btn btn-primary btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <div class="d-flex bd-highlight">
                                                                <div class="user_info">
                                                                    <span>Athan Jacoby</span>
                                                                    <p>10 Aug 2020</p>
                                                                </div>
                                                                <div class="ms-auto">
                                                                    <a href="javascript:void(0);" class="btn btn-primary btn-xs sharp me-1"><i class="fas fa-pencil-alt"></i></a>
                                                                    <a href="javascript:void(0);" class="btn btn-danger btn-xs sharp"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                -->
                <!--**********************************
            Chat box End
        ***********************************-->

                <!--**********************************
            Header start
        ***********************************-->
                <div class="header">
                    <div class="header-content">
                        <nav class="navbar navbar-expand">
                            <div class="collapse navbar-collapse justify-content-between">
                                <div class="header-left">
                                    <div class="dashboard_bar">
                                        <div style="font-size: 20px;" class="text-dark">HOSPITAL MANAGEMENT SYSTEM (HMS 2023)</div>
                                        <div style="font-size: 12px;" class=""><?= Yii::$app->params['dep_name'] ?></div>
                                    </div>
                                    <!--
                                    <div class="nav-item d-flex align-items-center">
                                                                        <div class="input-group search-area">
                                                                            <input type="text" class="form-control" placeholder="">
                                                                            <span class="input-group-text"><a href="javascript:void(0)"><i class="flaticon-381-search-2"></i></a></span>
                                                                        </div>
                                                                        <div class="plus-icon d-none">
                                                                            <a href="javascript:void(0);"><i class="fas fa-plus"></i></a>
                                                                        </div>
                                                                    </div>
                                    -->
                                </div>
                                <!--**********************************
                                      Top
                        ***********************************-->
                                <?= $this->render('top', ['page' => $page, 'userProfile' => @$userProfile]); ?>
                                <!--**********************************
                                                Top end
                                 ***********************************-->
                            </div>
                        </nav>
                    </div>
                </div>
                <!--**********************************
                    Header end ti-comment-alt
                ***********************************-->

                <!--**********************************
                    Sidebar start
                ***********************************-->
                <div class="dlabnav">
                    <div class="dlabnav-scroll">
                        <div class="dropdown header-profile2 ">
                            <?PHP if (!Yii::$app->user->isGuest) { ?>
                                <a class="nav-link " href="javascript:void(0);"  role="button" data-bs-toggle="dropdown">
                                    <div class="header-info2 d-flex align-items-center">
                                        <?= @Html::img($userProfile['pictureUrl'], ['class' => '', 'width' => '49']); ?>
                                        <div class="d-flex align-items-center sidebar-info">
                                            <div class="" >
                                                <span class="font-w400 d-block"><?= @Yii::$app->user->identity->profile->fullname; ?></span>
                                                <small class="text-end font-w400">(<?= @Yii::$app->user->identity->username; ?>) </small>
                                            </div>
                                            <i class="fas fa-chevron-down"></i>
                                        </div>
                                    </div>
                                </a>
                            <?PHP } ?>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="<?= Url::to(['/user/settings/profile']) ?>" class="dropdown-item ai-icon ">
                                    <svg  xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                    <span class="ms-2">Profile </span>
                                </a>
                                <a href="<?= Url::to(['/user/settings/profile']) ?>" class="dropdown-item ai-icon">
                                    <svg  xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    <span class="ms-2">Inbox </span>
                                </a>
                                <a href="<?= Url::to(['/user/security/logout']) ?>" class="dropdown-item ai-icon"  data-method="post">
                                    <svg  xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                                    <span class="ms-2">Logout </span>
                                </a>
                            </div>
                        </div>
                        <!--**********************************
                                      Menu
                        ***********************************-->
                        <?= $this->render('menu'); ?>
                        <!--**********************************
                                        Menu end
                         ***********************************-->
                        <div class="copyright">
                            <p><strong>ศูนย์เทคโนโลยีสารสนเทศและการสื่อสาร</strong> © 2024 All Rights Reserved</p>
                            <p class="fs-12">Made with <span class="heart"></span> by ศิลา กลั่นแกล้ว</p>
                        </div>
                    </div>
                </div>
                <!--**********************************
                    Sidebar end
                ***********************************-->
                <!--**********************************
            Content body start
        ***********************************-->
                <div class="content-body">
                    <!-- row -->
                    <div class="container-fluid">
                        <!--**********************************
                         Content
                        ***********************************-->
                        <?PHP if (!Yii::$app->user->isGuest) { ?>
                            <div class="alert alert-light alert-dismissible alert-alt fade show">
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="btn-close">
                                    <span><i class="mdi mdi-btn-close"></i></span>
                                </button>
                                <div class="media">
                                    <div class="media-body">
                                        <p class="mb-0"><b>ยินดีต้อนรับเข้าสู่ระบบ :: </b><b>ชื่อ  <?= @Yii::$app->user->identity->profile->fullname; ?></b> (<?= @Yii::$app->user->identity->profile->emp->dep->employee_dep_label; ?>)</p>
                                        <small> <?= @$userAuth ?></small>
                                    </div>
                                </div>
                            </div>

                        <?PHP } ?>

                        <?= $content ?>
                        <!--**********************************
                         Content end
                         ***********************************-->
                    </div>
                </div>
                <!--**********************************
                    Content body end
                ***********************************-->
                <!--**********************************
                    Footer start
                ***********************************-->
                <div class="footer">
                    <div class="copyright">
                        <p>Copyright © Designed &amp; Developed by <a href="javascript:;" target="_blank">คุณศิลา กลั่นแกล้ว</a> นักวิชาการคอมพิวเตอร์</p>
                    </div>
                </div>
                <!--**********************************
                    Footer end
                ***********************************-->
            </div>
            <!--**********************************
                Main wrapper end
            ***********************************-->
    <!--        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
            <?php $this->endBody() ?>
        </body>
    </html>
    <?php $this->endPage() ?>
<?PHP } ?>

