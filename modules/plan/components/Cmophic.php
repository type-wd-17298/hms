<?php

namespace app\modules\survay\components;

#use SoapClient;

use yii\httpclient\Client;

#use app\modules\mophic\models\TPersonCid;

class Cmophic {

    private function get_moph_access_token() {
        $session = \Yii::$app->session;
        $settings = \Yii::$app->params; //ระบค่าจากระบบ Setting
        $url = $settings['MOPH_URL'];
        $pwd = hash_hmac('sha256', $settings['MOPH_PASSWORD'], $settings['MOPH_SECRET_KEY']);
        $endpoint = $url . '/token?Action=get_moph_access_token&user=' . $settings['MOPH_USERNAME'] . '&password_hash=' . $pwd . '&hospital_code=' . $settings['MOPH_HCODE'];
        $client = new Client();
        $response = $client->createRequest()
                        ->setUrl($endpoint)
                        ->setMethod('get')
                        #->addHeaders(['Authorization' => 'Bearer ' . $token])
                        ->setOptions([
                            CURLOPT_CONNECTTIMEOUT => 30, //5 connection timeout
                            CURLOPT_TIMEOUT => 3600, //10 data receiving timeout
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => false
                        ])->send();

        $token = $response->getContent();

        if (!$session->has('MOPHTOKEN')) {
            $session->set('MOPHTOKEN', $token);
        }
        //return $response;
    }

    public static function searchCurrentByPID($cid) {

        $session = \Yii::$app->session;
        if (!$session->has('MOPHTOKEN')) {
            Cmophic::get_moph_access_token();
        }
        $token = $session->get('MOPHTOKEN');
        $data = [];
        $settings = \Yii::$app->params; //ระบค่าจากระบบ Setting
        $url = $settings['MOPH_URL'];

        $client = new Client(['baseUrl' => $url, 'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],]);
        $response = $client->createRequest()
                        //->setFormat(Client::FORMAT_JSON)
                        ->setUrl('api/ImmunizationTarget')
                        ->setMethod('get')
                        ->addHeaders(['Authorization' => 'Bearer ' . $token])
                        ->setData(['cid' => $cid])
                        ->setOptions([
                            CURLOPT_CONNECTTIMEOUT => 30, //5 connection timeout
                            CURLOPT_TIMEOUT => 3600, //10 data receiving timeout
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => false
                        ])->send();

        $data = @json_decode($response->getContent(), true);
        $return = @$data['result']['vaccine_history_count'];
        $return_vacc = @$data['result']['vaccine_history'];
        $updateSQLArray = [
            'source' => 'API',
            'vacc_covid_update' => new \yii\db\Expression('NOW()'),
            'vacc_covid' => $return
        ];
        if (isset($data['result']['vaccine_history'])) {
            foreach ($data['result']['vaccine_history'] as $value) {
                $updateSQLArray['vacc_' . $value['vaccine_plan_no']] = $value['vaccine_manufacturer_id'];
            }
        }
        #$db = \Yii::$app->db_mophic;
        #$db->createCommand()
        #->update('t_person_cid', $updateSQLArray, "CID = '{$cid}'")
        #->execute();
        #echo "<pre>";
        #print_r($updateSQLArray);
        #print_r($data['result']);
        #echo "</pre>";

        return ['vaccine_history_count' => $return, 'vaccine_history' => $return_vacc];
    }

    public static function searchVaccineByPID($cid) {
        $cid2 = str_replace('-', '', $cid); //ตัดคำ
        $session = \Yii::$app->session;
        if (!$session->has('MOPHTOKEN')) {
            Cmophic::get_moph_access_token();
        }
        $token = $session->get('MOPHTOKEN');
        $data = [];
        $settings = \Yii::$app->params; //ระบค่าจากระบบ Setting
        $url = $settings['MOPH_URL'];

        $client = new Client(['baseUrl' => $url, 'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],]);
        $response = $client->createRequest()
                        //->setFormat(Client::FORMAT_JSON)
                        ->setUrl('api/ImmunizationTarget')
                        ->setMethod('get')
                        ->addHeaders(['Authorization' => 'Bearer ' . $token])
                        ->setData(['cid' => $cid2])
                        ->setOptions([
                            CURLOPT_CONNECTTIMEOUT => 30, //5 connection timeout
                            CURLOPT_TIMEOUT => 3600, //10 data receiving timeout
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => false
                        ])->send();

        $data = @json_decode($response->getContent(), true);
        $return = @$data['result']['vaccine_history_count'];
        $return_vacc = @$data['result']['vaccine_history'];
        $updateSQLArray = [
                #'source' => 'API',
                #'vacc_covid_update' => new \yii\db\Expression('NOW()'),
                #'vacc_covid' => $return
        ];
        if (isset($data['result']['vaccine_history'])) {
            foreach ($data['result']['vaccine_history'] as $value) {
                $updateSQLArray['t' . $value['vaccine_plan_no']] = $value['vaccine_name'];
                $updateSQLArray['d' . $value['vaccine_plan_no']] = substr($value['immunization_datetime'], 0, 10);
            }
        }
        $db = \Yii::$app->db_covid;
        $cc = count($updateSQLArray);
        if ($cc > 0) {
            $db->createCommand()
                    ->update('person_risk_wave2', ['vaccine' => "{$cc}"], "cid = '{$cid}'")
                    ->execute();
        }

        #echo "<pre>";
        #print_r($updateSQLArray);
        #print_r($data['result']);
        #echo "</pre>";

        return ['vaccine_history_count' => $return, 'vaccine_history' => $return_vacc];
    }

    public static function searchByLabPID($cid) {
        $cid2 = str_replace('-', '', $cid); //ตัดคำ
        $session = \Yii::$app->session;
        if (!$session->has('MOPHTOKEN')) {
            Cmophic::get_moph_access_token();
        }
        $token = $session->get('MOPHTOKEN');
        $data = [];
        $settings = \Yii::$app->params; //ระบค่าจากระบบ Setting
        $url = $settings['MOPH_URL'];

        $client = new Client(['baseUrl' => $url, 'responseConfig' => [
                'format' => Client::FORMAT_JSON
            ],]);
        $response = $client->createRequest()
                        //->setFormat(Client::FORMAT_JSON)
                        ->setUrl('api/ImmunizationTarget')
                        ->setMethod('get')
                        ->addHeaders(['Authorization' => 'Bearer ' . $token])
                        ->setData(['cid' => $cid2])
                        ->setOptions([
                            CURLOPT_CONNECTTIMEOUT => 30, //5 connection timeout
                            CURLOPT_TIMEOUT => 3600, //10 data receiving timeout
                            CURLOPT_SSL_VERIFYHOST => 0,
                            CURLOPT_SSL_VERIFYPEER => false
                        ])->send();

        $data = @json_decode($response->getContent(), true);
        $return = @$data['result']['vaccine_history_count'];
        $return_vacc = @$data['result']['vaccine_history'];
        $updateSQLArray = [
                #'source' => 'API',
                #'vacc_covid_update' => new \yii\db\Expression('NOW()'),
                #'vacc_covid' => $return
        ];
        /*
          if (isset($data['result']['vaccine_history'])) {
          foreach ($data['result']['vaccine_history'] as $value) {
          $updateSQLArray['t' . $value['vaccine_plan_no']] = $value['vaccine_name'];
          $updateSQLArray['d' . $value['vaccine_plan_no']] = substr($value['immunization_datetime'], 0, 10);
          }
          }
          /*
          $db = \Yii::$app->db_covid;
          $cc = count($updateSQLArray);
          if ($cc > 0) {
          $db->createCommand()
          ->update('person_risk_wave2', ['vaccine' => "{$cc}"], "cid = '{$cid}'")
          ->execute();
          }
         */
        #echo "<pre>";
        #print_r($updateSQLArray);
        #print_r($data['result']['lab_test_results']);
        #echo "</pre>";

        return $data['result']['lab_test_results'];
    }

}
