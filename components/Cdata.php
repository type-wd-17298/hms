<?php

namespace app\components;

use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\httpclient\Client;

class Cdata {

    public static function getUserOnline() {
        $sqlQuery = "SELECT count(distinct user_id) AS cc FROM session WHERE user_id IS NOT NULL ";
        $model = \yii::$app->db->createCommand($sqlQuery)->queryOne();
        return $model['cc'];
    }

    public static function getAuthUser($userID = null) {
        $auth = \Yii::$app->authManager->getRolesByUser($userID);
        $au = '';
        foreach ($auth as $role) {
            $au .= $role->description . ',';
        }
        $au = rtrim($au, ',');
        return $au;
    }

    public static function getDataUserAccount($id = '') {
        try {
            $connection = \yii::$app->db;
            if (empty($id))
                $id = \Yii::$app->user->identity->id;
            $sqlQuery = "SELECT data,u.email "
                    . "FROM  app_profile p "
                    . "LEFT JOIN app_social_account s  ON p.user_id = s.user_id "
                    . "LEFT JOIN app_user u  ON p.user_id = u.id "
                    . "WHERE p.user_id = '{$id}' OR cid = '{$id}'";

            $model = $connection->createCommand($sqlQuery)->queryOne();
            $data = @json_decode($model['data'], true);
            if (is_array($data)) {
                return @array_merge($model, $data);
            } else {
                return $model;
            }
        } catch (\Exception $ex) {
            return [];
        }
    }

    public static function getDataUserOnline() {
        try { //update data ใน social_account LINE
            $connection = \yii::$app->db;
            $id = \Yii::$app->user->identity->id;
            $sqlQuery = "SELECT * FROM session WHERE user_id = {$id} ORDER BY last_write desc LIMIT 1 ";
            $model = $connection->createCommand($sqlQuery)->queryOne();
            $sessionTranform = explode('s:236:', $model['data']);
            $sessionTranform = explode(';s:10:', $sessionTranform[1]);
            $token = str_replace('"', '', $sessionTranform[0]);
            if (strlen($token) == 236) {
                $client = new Client();
                $response = $client->createRequest()
                                ->setUrl('https://api.line.me/v2/profile')
                                ->setMethod('get')
                                ->addHeaders(['Authorization' => 'Bearer ' . $token])
                                ->setOptions([
                                    CURLOPT_CONNECTTIMEOUT => 30, //5 connection timeout
                                    CURLOPT_TIMEOUT => 3600, //10 data receiving timeout
                                    CURLOPT_SSL_VERIFYHOST => 0,
                                    CURLOPT_SSL_VERIFYPEER => false
                                ])->send();

                $return = $connection->createCommand()
                        ->update('app_social_account', ['data' => $response->content], ['provider' => 'line', 'user_id' => $id])
                        ->execute();
                return $return;
            } else {
                return '';
            }
        } catch (\Exception $exc) {
            //echo $exc->getMessage();
            return '';
        }
    }

    public static function data_session_decode($data) {
        $data = array_filter(explode(';', $data));
        $list_data = array();
        foreach ($data as $key => $dat) {
            if (!empty($dat)) {
                $data[$key] = array_filter(explode('|', $dat));
                if (substr($data[$key][1], 0, 1) == 'i') {
                    $list_data[$data[$key][0]] = strstr($data[$key][1], ':');
                    $list_data[$data[$key][0]] = substr($list_data[$data[$key][0]], 1);
                } elseif (substr($data[$key][1], 0, 1) == 's') {
                    $list_data[$data[$key][0]] = strstr($data[$key][1], '"    ');
                    $list_data[$data[$key][0]] = substr($list_data[$data[$key][0]], 0, -1);
                    $list_data[$data[$key][0]] = substr($list_data[$data[$key][0]], 1);
                } else {
                    unset($data[$key]);
                }
            }
        }
        unset($data);
        return $list_data;
    }

}
