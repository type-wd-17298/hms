<?php

namespace app\modules\line\components;

use Yii;
use yii\httpclient\Client;
use app\components\Ccomponent;

#use app\modules\store\models\SystemsAlert;

class lineBot {

    public $tokenApi = "https://notify-api.line.me/api/notify";
    public $token = "";
    public $tag = "";

    public static function LineLink() {
        $client_id = \Yii::$app->params['clientIdline'];
        $api_url = 'https://notify-bot.line.me/oauth/authorize?';
        $callback_url = \yii\helpers\Url::to(['//line/default/linecallback'], 'https');
        $query = [
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $callback_url,
            'scope' => 'notify',
            'state' => 'HMS-Notify'
        ];
        $result = $api_url . http_build_query($query);
        return $result;
    }

    public static function line() {
        $client_id = \Yii::$app->params['clientIdline']; //JN
        $client_secret = \Yii::$app->params['clientSecretline'];
        $api_url = 'https://notify-bot.line.me/oauth/token';
        $callback_url = \yii\helpers\Url::to(['//line/default/linecallback'], 'https');
        parse_str($_SERVER['QUERY_STRING'], $queries);
        $fields = [
            'grant_type' => 'authorization_code',
            'code' => $queries['code'],
            'redirect_uri' => $callback_url,
            'client_id' => $client_id,
            'client_secret' => $client_secret
        ];
        try {
            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_POST, count($fields));
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $res = curl_exec($ch);
            curl_close($ch);

            if ($res == false)
                throw new Exception(curl_error($ch), curl_errno($ch));

            $json = json_decode($res);

            //var_dump($json);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
            //var_dump($e);
        }

        return $json;
    }

    public static function send($message, $lineToken = []) {
        $tokenApi = "https://notify-api.line.me/api/notify";
        #$alert = SystemsAlert::findOne(['alert_tag' => $this->tag]);

        if (count($lineToken) > 0) {
            $rowAlert = $lineToken;
        } #else {
        #$rowAlert = json_decode($alert->token);
        #}
        //print_r($rowAlert);
        //exit;
        foreach ($rowAlert as $index => $token) {
            // $this->token = $token;

            $stickerPackageId = rand(1, 2);
            $stickerId = ($stickerPackageId == 1 ? rand(100, 139) : rand(18, 47));
			try {
				$client = new Client();
				$response = $client->createRequest()
								->setUrl($tokenApi)
								->setMethod('post')
								->setData([
									'message' => Ccomponent::getThaiDate(date('Y-m-d H:i:s'), 'S', true) . "\n" . $message . "\nhttps://somdej17.spo.moph.go.th/web",
										#'stickerId' => $stickerId,
										#'stickerPackageId' => $stickerPackageId,
								])
								->addHeaders(['Authorization' => 'Bearer ' . $token])
								->setOptions([
									CURLOPT_CONNECTTIMEOUT => 30, //5 connection timeout
									CURLOPT_TIMEOUT => 3600, //10 data receiving timeout
									CURLOPT_SSL_VERIFYHOST => 0,
									CURLOPT_SSL_VERIFYPEER => false
								])->send();
				if ($response->isOk) {
					$resp = $response->content;
				} else {
					$resp = $response->content;
				}
			} catch (\Exception $e) {
				// Log the error or handle it
				$resp = 'Error: ' . $e->getMessage();
			}	
        }
        return $resp;
    }

}
