<?php

namespace app\modules\line\components;

use yii\base\Component;
use \LINE\LINEBot;
use \LINE\LINEBot\HTTPClient;
use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
//use LINE\LINEBot\Event;
//use LINE\LINEBot\Event\BaseEvent;
//use LINE\LINEBot\Event\MessageEvent;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\LocationMessageBuilder;
use LINE\LINEBot\MessageBuilder\AudioMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\ImagemapActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ImageCarouselColumnTemplateBuilder;
use LINE\LINEBot\QuickReplyBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use app\modules\line\models\StaffRegister;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\FillerComponentBuilder;
use app\modules\epayslip\components\Cdata;
use app\modules\epayslip\components\Ccomponent;

#use app\modules\line\components\CAttendance;

class CLine extends Component {

    public $app = null;
    public $env = [];

    public function init() {
        parent::init();
// initiate app
        $configs = [
            'settings' => ['displayErrorDetails' => true],
        ];
        $this->app = new \Slim\App($configs);
        $this->env = \Yii::$app->params;
    }

    public static function getUser($cid = '', $targetLine = [], $lookup = '') {
        $where = '';
        $targetLine = implode(',', $targetLine);
        switch ($lookup) {
            case 9://หน่วยงาน
                $where = '';
                break;
            case 1://กลุ่มหน่วยงาน
                $where = " AND d.department_group_id IN ({$targetLine})";
                break;
            case 2://หน่วยงาน
                $where = " AND e.department_id IN ({$targetLine})";
                break;
            case 3://รายบุคคล
                $where = " AND e.employee_id IN ({$targetLine})";
                break;
            default:
                $where = " AND p.cid = '{$cid}'";
                break;
        }

        $sqlQuery = "select
                        s.client_id AS user_id
                        ,p.cid
                        ,CONCAT(p.name,' ',p.lname) AS fullname
                        from `profile` p
                        INNER JOIN social_account s ON p.user_id = s.user_id
                        LEFT JOIN attendance.employee e ON md5(e.employee_cid) = md5(p.cid)
                        LEFT JOIN attendance.department d ON e.department_id = d.department_id
                        WHERE provider = 'line'
                        {$where}
                        GROUP BY p.cid";

        return \Yii::$app->db->createCommand($sqlQuery)->queryAll();
    }

    public static function pushMessage($arrayMessage) {
        //กำหนดค่าตัวแปร
        $_ENV = \Yii::$app->params;
        $httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
        $bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
        foreach ($arrayMessage as $data) {
            $messageData = new TextMessageBuilder($data['message']);
            $response = $bot->pushMessage($data['userId'], $messageData);
        }
    }

    public static function beaconConnect() {
        $jsonData = [];
        $_ENV = \Yii::$app->params;

        $jsonArray = json_decode(file_get_contents('php://input'), true);
        if (is_array($jsonArray) && @$jsonArray["events"][0]['type'] == 'beacon') {
            $httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
            $bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
            $jsonData = $jsonArray["events"][0];
            $replyToken = $jsonData["replyToken"];
            $beacon = $jsonData["beacon"];
            $source = $jsonData["source"];
            $userId = $source['userId'];
            $timestamp = $jsonData["timestamp"];
            $beacon_type = $beacon['type'];
            //$beacon_hwid = $beacon['hwid'];
            $userData = $bot->getProfile($userId)->getJSONDecodedBody();
            //$message = json_encode($userData);
            $messageData = @CBeacon::build($userData, $beacon['hwid']);
            //$messageData = new TextMessageBuilder($message);
            $response = $bot->replyMessage($replyToken, $messageData);
        }
    }

    public function start($route = '/') {

        //Beacon;
        //self::beaconConnect();

        //กำหนดค่าตัวแปร
        $_ENV = \Yii::$app->params;

        /* ROUTES */
        $this->app->get($route, function ($request, $response) {
            return "SPO-Connect!";
        });

        $this->app->post($route, function ($request, $response) {

// get request body and line signature header
            $body = file_get_contents('php://input');
            $signature = $_SERVER['HTTP_X_LINE_SIGNATURE'];

// log body and signature
            file_put_contents('php://stderr', 'Body: ' . $body);

// is LINE_SIGNATURE exists in request header?
            if (empty($signature)) {
                return $response->withStatus(400, 'Signature not set');
            }

// is this request comes from LINE?
            if ($_ENV['PASS_SIGNATURE'] == false && !SignatureValidator::validateSignature($body, $_ENV['CHANNEL_SECRET'], $signature)) {
                return $response->withStatus(400, 'Invalid signature');
            }

// init bot
            $httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
            $bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
            $events = json_decode($body, true);

            $replyToken = $events['events'][0]['replyToken'];
            $userID = $events['events'][0]['source']['userId'];
            $userData = $bot->getProfile($userID)->getJSONDecodedBody();
            $sourceType = $events['events'][0]['source']['type'];
            $is_postback = null;
            $is_message = null;

            if (isset($events['events'][0]) && array_key_exists('message', $events['events'][0])) {
                $is_message = true;
                $typeMessage = $events['events'][0]['message']['type'];
                $userMessage = $events['events'][0]['message']['text'];
                $idMessage = $events['events'][0]['message']['id'];
            }

            foreach ($events['events'] as $event) {
                if ($event['type'] == 'follow') {
                    $userData = $bot->getProfile($event['source']['userId'])->getJSONDecodedBody();
                    $message = CLine::addUser($userData);
                    $replyData = new TextMessageBuilder($message);
                    $response = $bot->replyMessage($event['replyToken'], $replyData);
                }

                $userMessage = $event['message']['text'];
                $typeMessage = strtolower($userMessage);
                switch ($typeMessage) {
#case 'token':
#$message = "กำลังดำเนินการ ในส่วนนี้อยู่ค่ะ คุณศิลา ";
#$replyData = new TextMessageBuilder($message);
#break;
                    
                    case 'ลงทะเบียน':
                        $message = "ข้อตกลงในการให้ความยินยอมในการเก็บรวบรวม และใช้ข้อมูลส่วนบุคคล เพื่อประโยชน์ในการใช้ Line Official Account สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี ให้บริการข้อมูล eOffice สำหรับเจ้าหน้าที่สาธารณสุข และให้บริการข้อมูลข่าวสารที่เกี่ยวข้องกับข้อมูลสุภาพของประชาชน  ด้วยตนเองผ่านมือถือ สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี "
                                . "ขอให้ผู้ใช้งานแสดงเจตนายินยอมให้ สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี เก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลดังกล่าว ทั้งนี้ภายใต้วัตถุประสงค์ในการดำเนินการด้านสาธารณสุข \n\nในการนี้ข้าพเจ้า ผู้ใช้งานได้อ่านและเข้าใจรายละเอียดการขอความยินยอมข้างต้น และทราบว่า หากไม่ยินยอมข้าพเจ้าจะไม่สามารถใช้ Line Official Account สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี ได้ ";
                        $textReplyMessage = new BubbleContainerBuilder(
                                "ltr", // กำหนด NULL หรือ "ltr" หรือ "rtl"
                                NULL, NULL, new BoxComponentBuilder(
                                        "horizontal", array(
                                    new TextComponentBuilder($message, NULL, NULL, NULL, NULL, NULL, true)
                                        )
                                ), new BoxComponentBuilder(
                                        "horizontal", array(
                                    new ButtonComponentBuilder(
                                            new UriTemplateActionBuilder("ยอมรับ", "https://liff.line.me/1654916839-xag48dva"), NULL, NULL, NULL, "primary"
                                    ),
                                    //new FillerComponentBuilder(),
                                    new ButtonComponentBuilder(
                                            new UriTemplateActionBuilder("ไม่ยอมรับ", "https://liff.line.me/1654916839-xag48dva"), NULL, NULL, NULL, "secondary"
                                    )))
                        );

                        $replyData = new FlexMessageBuilder("ข้อตกลงในการให้ความยินยอมในการเก็บรวบรวม และใช้ข้อมูลส่วนบุคคล", $textReplyMessage);
                        break;

                    

                    case "l":
                        $textReplyMessage = "Bot ตอบกลับคุณเป็นข้อความ";
                        $textMessage = new TextMessageBuilder($textReplyMessage);

                        $picFullSize = 'https://www.e-idsolutions.com/wp-content/uploads/2016/08/healthcare-bg.jpg';
                        $picThumbnail = 'https://www.inventhealth.co.uk/wp-content/uploads/2016/06/quality-bg.jpg';
                        $imageMessage = new ImageMessageBuilder($picFullSize, $picThumbnail);

                        $placeName = "ที่ตั้งสำนักงานสาธารณสุขจังหวัดสุพรรณบุรี";
                        $placeAddress = "แขวง พลับพลา เขต วังทองหลาง กรุงเทพมหานคร ประเทศไทย";
                        $latitude = 13.780401863217657;
                        $longitude = 100.61141967773438;
                        $locationMessage = new LocationMessageBuilder($placeName, $placeAddress, $latitude, $longitude);
                        $multiMessage = new MultiMessageBuilder;
                        $multiMessage->add($textMessage);
                        $multiMessage->add($imageMessage);
                        $multiMessage->add($locationMessage);
                        $replyData = $multiMessage;
                        break;
                    
                    case 'สรุปรายการเงินเดือน':
                        //เรียกใช้งานข้อมูลเงินเดือน
                        $replyData = @CSlip::getList($event, $userData);
                        break;
                    
                    case "p":
// เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
// ดึงค่ามาแบบเป็น JSON String โดยใช้คำสั่ง getRawBody() กรณีเป้นข้อความ text
                            $textReplyMessage = $response->getRawBody(); // return string
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        }
// กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;
                    case "สวัสดี":
// เรียกดูข้อมูลโพรไฟล์ของ Line user โดยส่งค่า userID ของผู้ใช้ LINE ไปดึงข้อมูล
                        $response = $bot->getProfile($userID);
                        if ($response->isSucceeded()) {
// ดึงค่าโดยแปลจาก JSON String .ให้อยู่ใรูปแบบโครงสร้าง ตัวแปร array
                            $userData = $response->getJSONDecodedBody(); // return array
                            $textReplyMessage = 'สวัสดีครับ คุณ ' . $userData['displayName'];
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        }
// กรณีไม่สามารถดึงข้อมูลได้ ให้แสดงสถานะ และข้อมูลแจ้ง ถ้าไม่ต้องการแจ้งก็ปิดส่วนนี้ไปก็ได้
                        $failMessage = json_encode($response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
                        break;
                    case "test":
                        $textPushMessage = print_r($events['events'], true);
                        $messageData = new TextMessageBuilder($textPushMessage);
                        break;
                    case "token":
//$replyData = new TextMessageBuilder(print_r($bot->getProfile($userID),true));
                        $userId = 'U4309be54d98128d02d27a59dcf4dbf1b';
                        $userIds = ['U4309be54d98128d02d27a59dcf4dbf1b'];

// ทดสอบส่ง push ข้อความอย่างง่าย
#$textPushMessage = 'สวัสดีครับ';
#$messageData = new TextMessageBuilder($textPushMessage);
//ส่งหา user ที่กำหนด
#$response = $bot->pushMessage($userId,$messageData);
                        $textPushMessage = 'บัญชี XXX-X-XX651-0 : เงินเดือนเข้า +30,813.15 บาท';
                        $messageData = new TextMessageBuilder($textPushMessage);
//ส่งหลายคนพร้อมกัน
                        $response = $bot->multicast($userIds, $messageData);
//ส่งเป็นกลุ่ม
//$response = $bot->broadcast($messageData);

                        if ($response->isSucceeded()) {
                            echo 'success';
                            return;
                        }
                        break;
                    case "me":
                        $response = $bot->getProfile($userID);
                        $userData = $response->getJSONDecodedBody();
                        $replyData = new TextMessageBuilder($userData['userId']);
                        break;
                    

                    case 'c':
                        $quickReply = new QuickReplyMessageBuilder(
                                array(
                            new QuickReplyButtonBuilder(new LocationTemplateActionBuilder('Location')),
                            new QuickReplyButtonBuilder(new CameraTemplateActionBuilder('Camera')),
                            new QuickReplyButtonBuilder(new CameraRollTemplateActionBuilder('Camera roll')),
                                )
                        );
                        $textReplyMessage = "ส่งพร้อม quick reply ";
                        $replyData = new TextMessageBuilder($textReplyMessage, $quickReply);
                        break;

                    default:
                        $textReplyMessage = " คุณไม่ได้พิมพ์ ค่า ตามที่กำหนด";
                        $replyData = new TextMessageBuilder($textReplyMessage);
                        break;
                }
                $response = $bot->replyMessage($event['replyToken'], $replyData);
                if ($response->isSucceeded()) {
                    echo 'Succeeded!';
                    return;
                }

// Failed
                echo $response->getHTTPStatus() . ' ' . $response->getRawBody();
            }
        });

        $this->app->run();
    }

    /* กรณี user เพิ่มเป็นเพื่อน */

    public static function addUser($data) {

        try {
            $model = new StaffRegister();
            $model->user_id = $data['userId'];
            $model->user_data = $data['pictureUrl'];
            $model->date_create = new \yii\db\Expression('NOW()');
            $model->user_event = 'follow';
            $model->save();
            return 'เพิ่มข้อมูลเรียบร้อยค่ะ';
        } catch (\Exception $exc) {
            return $exc->getMessage();
        }
    }

    public static function epayslip() {
        $models = StaffRegister::find()->all();
        foreach ($models as $model) {
            if (strlen($model->staff->profile->cid) == 13) {
                $userData = $bot->getProfile($event['source']['userId'])->getJSONDecodedBody();
                $message = '';
                $replyData = new TextMessageBuilder($message);
                $response = $bot->replyMessage($event['replyToken'], $replyData);
            }
        }
    }

}
