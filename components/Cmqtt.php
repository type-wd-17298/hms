<?php

namespace app\components;

//use app\models\Cdepartment;
use \PhpMqtt\Client\MqttClient;
use \PhpMqtt\Client\ConnectionSettings;

class Cmqtt {

    public static function public($topic, $message = '') {
		/*
        $app = \Yii::$app;
        $clientId = uniqid();
        $clean_session = false;
        $mqtt_version = MqttClient::MQTT_3_1_1;
        $connectionSettings = (new ConnectionSettings)
                //->setUsername($username)
                //->setPassword($password)
                ->setKeepAliveInterval(160)
                //->setUseTls(true)
                //->setLastWillTopic('emqx/test/last-will')
                ->setLastWillMessage('client disconnect')
                ->setLastWillQualityOfService(1);

        $mqtt = new MqttClient($app->params['mqtt_host'], $app->params['mqtt_port'], $clientId);
        $mqtt->connect($connectionSettings, $clean_session);
        $mqtt->publish($topic, json_encode([$message]), 0, false);
	*/	
    }
	

}
