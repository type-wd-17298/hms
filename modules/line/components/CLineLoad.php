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

class CLineLoad extends Component {

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

    public static function pushMessage($arrayMessage) {
        //กำหนดค่าตัวแปร
        $_ENV = \Yii::$app->params;
        $httpClient = new CurlHTTPClient($_ENV['CHANNEL_ACCESS_TOKEN']);
        $bot = new LINEBot($httpClient, ['channelSecret' => $_ENV['CHANNEL_SECRET']]);
        foreach ($arrayMessage as $data) {
            $messageData = new TextMessageBuilder($data['message']);
            $response = $bot->pushMessage($data['userId'], $messageData);
        }


//        if ($response->isSucceeded()) {
//            echo 'Succeeded!';
//                return;
//        }
    }

    public function start($route = '/') {
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
                    case 'eslip':
                        self::epayslip();
                        break;
                    case 'ลงทะเบียน':
                        /*
                          $replyData = new TemplateMessageBuilder('ยืนยันการสมัครใช้งานและเข้าสู่ระบบ SPO Connect', new ConfirmTemplateBuilder(
                          "ข้อตกลงในการให้ความยินยอมในการเก็บรวบรวม และใช้ข้อมูลส่วนบุคคล เพื่อประโยชน์ในการใช้ Line Official Account สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี ให้บริการข้อมูล eOffice สำหรับเจ้าหน้าที่สาธารณสุข และให้บริการข้อมูลข่าวสารที่เกี่ยวข้องกับข้อมูลสุภาพของประชาชน  ด้วยตนเองผ่านมือถือ สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี "
                          . "ขอให้ผู้ใช้งานแสดงเจตนายินยอมให้ สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี เก็บรวบรวม ใช้ หรือเปิดเผยข้อมูลส่วนบุคคลดังกล่าว ทั้งนี้ภายใต้วัตถุประสงค์ในการดำเนินการด้านสาธารณสุข \nในการนี้ข้าพเจ้า ผู้ใช้งานได้อ่านและเข้าใจรายละเอียดการขอความยินยอมข้างต้น และทราบว่า หากไม่ยินยอมข้าพเจ้าจะไม่สามารถใช้ Line Official Account สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี ได้ ", // ข้อความแนะนำหรือบอกวิธีการ หรือคำอธิบาย
                          array(
                          new MessageTemplateActionBuilder(
                          'ยอมรับ', // ข้อความสำหรับปุ่มแรก
                          'YES' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                          ),
                          new MessageTemplateActionBuilder(
                          'ไม่ยอมรับ', // ข้อความสำหรับปุ่มแรก
                          'NO' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                          ),
                          )
                          )
                          );
                         *
                         */

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
                                    new UriTemplateActionBuilder("ยอมรับ", "http://s"), NULL, NULL, NULL, "primary"
                            ),
                            //new FillerComponentBuilder(),
                            new ButtonComponentBuilder(
                                    new UriTemplateActionBuilder("ไม่ยอมรับ", "http://s"), NULL, NULL, NULL, "secondary"
                            )
                                )
                                )
                        );

                        $replyData = new FlexMessageBuilder("ข้อตกลงในการให้ความยินยอมในการเก็บรวบรวม และใช้ข้อมูลส่วนบุคคล", $textReplyMessage);



                        break;

                    case 'บริการ1':
                        $actions = array(
                            new MessageTemplateActionBuilder("ข้อมูลส่วนบุคคล", "text 1"),
                            new UriTemplateActionBuilder("ข้อมูลรับบริการ", "http://www.google.com"),
                            new PostbackTemplateActionBuilder("ข้อมูลการตรวจสุขภาพ", "page=3"),
                            new PostbackTemplateActionBuilder("การเปิดเผยข้อมูลส่วนบุคคล", "page=3"),
                        );

                        $img_url = 'https://lightfarm.co.th/wp-content/uploads/2018/03/background-%E0%B8%9F%E0%B8%A5%E0%B8%B1%E0%B8%94%E0%B9%84%E0%B8%A5%E0%B8%97%E0%B9%8C50W.jpg'; //"https://i.ytimg.com/vi/RgQOfFs1iAY/maxresdefault.jpg";
                        $button = new ButtonTemplateBuilder("บริการระบบสารสนเทศหน่วยงาน", "บริการจากสำนักงานสาธารณสุขจังหวัดสุพรรณบุรี", $img_url, $actions);
                        $replyData = new TemplateMessageBuilder("บริการระบบข้อมูลสุขภาพ", $button);
                        break;
                    case 'บริการอื่นๆ':
                        $actionBuilder = array(
                            new MessageTemplateActionBuilder(
                                    'Message Template', // ข้อความแสดงในปุ่ม
                                    'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new UriTemplateActionBuilder(
                                    'Uri Template', // ข้อความแสดงในปุ่ม
                                    'https://www.ninenik.com'
                            ),
                            new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action' => 'buy',
                                        'item' => 100,
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        );


                        $actionBuilder = array(
                            new MessageTemplateActionBuilder("ข้อมูลส่วนบุคคล", "text 1"),
                            //new UriTemplateActionBuilder("ข้อมูลรับบริการ", "http://www.google.com"),
                            new PostbackTemplateActionBuilder("ข้อมูลการตรวจสุขภาพ", "page=3"),
                            new PostbackTemplateActionBuilder("การเปิดเผยข้อมูลส่วนบุคคล", "page=3"),
                        );


#$img_url = 'https://www.healthinomics.com/wp-content/uploads/2016/09/medical-bg-healthinomics.png';
#$button = new ButtonTemplateBuilder("บริการข้อมูลสุขภาพ", "แสดงข้อมูลย้อนหลัง  1 สัปดาห์", $img_url, $actions);
#$replyData = new TemplateMessageBuilder("บริการระบบข้อมูลสุขภาพ", $button);

                        $replyData = new TemplateMessageBuilder('บริการสุขภาพ', new CarouselTemplateBuilder(
                                [
                            new CarouselColumnTemplateBuilder(
                                    'บริการข้อมูลสุขภาพ', 'แสดงข้อมูลย้อนหลัง 6 เดือน', 'https://www.healthinomics.com/wp-content/uploads/2016/09/medical-bg-healthinomics.png', $actionBuilder
                            ),
                            new CarouselColumnTemplateBuilder(
                                    'บริการข้อมูลการตรวจ Lab', 'แสดงข้อมูลย้อนหลัง 6 เดือน', 'https://www.healthinomics.com/wp-content/uploads/2016/09/medical-bg-healthinomics.png', $actionBuilder
                            ),
                            new CarouselColumnTemplateBuilder(
                                    'บริการข้อมูลการนัดหมาย', 'สำหรับผู้รับบริการของรัฐ โรคเบาหวานความดัน', 'https://www.healthinomics.com/wp-content/uploads/2016/09/medical-bg-healthinomics.png', $actionBuilder
                            ),
                                ]
                                )
                        );


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
                    case "บริการต่างๆ":
// กำหนด action 4 ปุ่ม 4 ประเภท
                        $actionBuilderEpaySlip = [
                            new MessageTemplateActionBuilder(
                                    'สรุปรายการเงินเดือน', // ข้อความแสดงในปุ่ม
                                    'สรุปรายการเงินเดือน' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new UriTemplateActionBuilder(
                                    'Uri Template', // ข้อความแสดงในปุ่ม
                                    'https://www.ninenik.com'
                            ),
                            new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action' => 'buy',
                                        'item' => 100,
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        ];
                        $actionBuilderEAdtentime = [
                            new MessageTemplateActionBuilder(
                                    'สรุปรายการลงชื่อเข้าออก', // ข้อความแสดงในปุ่ม
                                    'สรุปรายการลงชื่อเข้าออก' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                    'บันทึกการไปราชการ', // ข้อความแสดงในปุ่ม
                                    'บันทึกการไปราชการ'
                            ),
                            new MessageTemplateActionBuilder(
                                    'บันทึกการลา', // ข้อความแสดงในปุ่ม
                                    'บันทึกการลา'
                            ),
                        ];
                        $actionBuilderRegister = [
                            new MessageTemplateActionBuilder(
                                    'ลงทะเบียนเข้าประชุม', // ข้อความแสดงในปุ่ม
                                    'ลงทะเบียน' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new MessageTemplateActionBuilder(
                                    'แสดงรายการประชุม', // ข้อความแสดงในปุ่ม
                                    'รายการประชุม'
                            ),
                            new MessageTemplateActionBuilder(
                                    'เช็คอินเข้า-ออกการประชุม', // ข้อความแสดงในปุ่ม
                                    'เช็คอินเข้า-ออกการประชุม'
                            ),
                        ];
                        $actionBuilder = [
                            new MessageTemplateActionBuilder(
                                    'Message Template', // ข้อความแสดงในปุ่ม
                                    'This is Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                            new UriTemplateActionBuilder(
                                    'Uri Template', // ข้อความแสดงในปุ่ม
                                    'https://www.ninenik.com'
                            ),
                            new PostbackTemplateActionBuilder(
                                    'Postback', // ข้อความแสดงในปุ่ม
                                    http_build_query(array(
                                        'action' => 'buy',
                                        'item' => 100,
                                    )), // ข้อมูลที่จะส่งไปใน webhook ผ่าน postback event
                                    'Postback Text' // ข้อความที่จะแสดงฝั่งผู้ใช้ เมื่อคลิกเลือก
                            ),
                        ];
                        $replyData = new TemplateMessageBuilder('บริการระบบสารสนเทศ', new CarouselTemplateBuilder(
                                [
                            /*
                              new CarouselColumnTemplateBuilder(
                              'บริการข้อมูลสุขภาพ', 'แสดงข้อมูลย้อนหลัง 6 เดือน', 'https://www.healthinomics.com/wp-content/uploads/2016/09/medical-bg-healthinomics.png', $actionBuilder
                              ),
                              new CarouselColumnTemplateBuilder(
                              'บริการข้อมูลการตรวจ Lab', 'แสดงข้อมูลย้อนหลัง 6 เดือน', 'https://www.healthinomics.com/wp-content/uploads/2016/09/medical-bg-healthinomics.png', $actionBuilder
                              ),
                             *
                             */
                            new CarouselColumnTemplateBuilder(
                                    'บริการลงทะเบียนเข้าร่วมประชุม', 'ระบบลงทะเบียนสำหรับการเข้าร่วมการประชุม', 'https://www.krungsriassetonline.com/100mbclub/upload/mil_register_bg_842_1402022233.83986_img1.jpg', $actionBuilderRegister
                            ),
                            new CarouselColumnTemplateBuilder(
                                    'บริการข้อมูล ePaySlip', 'บริการแสดงรายการข้อมูลเงินเดือนออนไลน์', 'https://gamingroom.co/wp-content/uploads/2017/09/Non-solo-social-ecco-come-gli-italiani-usano-Internet.jpg', $actionBuilderEpaySlip
                            ),
                            new CarouselColumnTemplateBuilder(
                                    'บริการระบบ CheckIN เข้าออกงาน', 'ให้บริการเฉพาะ สนง.สาธารณสุขจังหวัดสุพรรณบุรี', 'https://mobidev.biz/wp-content/uploads/2020/01/multimodal-biometrics-recognition-identification-1-2048x939.png', $actionBuilderEAdtentime
                            ),
                            new CarouselColumnTemplateBuilder(
                                    'บริการระบบ eOffice', 'ระบบ eOffice สำหรับสำนักงาน', 'https://img.freepik.com/free-photo/blurred-business-workplace-background_23-2148187123.jpg?size=626&ext=jpg', $actionBuilder
                            ),
                                ]
                                )
                        );
                        break;
                    case 'สรุปรายการเงินเดือน':

                        //เรียกใช้งานข้อมูลเงินเดือน
                        $user = Cdata::getStatement($event['source']['userId']);
                        if (!isset($user['eslip01'])) {
                            $textReplyMessage = 'ระบบไม่พบข้อมูลของ คุณ ' . $userData['displayName'] . ' ค่ะ';
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        }
                        $textReplyMessage = new BubbleContainerBuilder(
                                "ltr", // กำหนด NULL หรือ "ltr" หรือ "rtl"
                                new BoxComponentBuilder(
                                "vertical", array(
                            new TextComponentBuilder("ใบแจ้งรายการเงินเดือน", NULL, NULL, "md", "center"),
                            new TextComponentBuilder(Ccomponent::getThaiMonth($user['eslip02'], 'SS') . ' ' . $user['eslip01'], NULL, NULL, "xl", "center")
                                )
                                ), new ImageComponentBuilder(
                                "https://www.deutschebank.co.in/img/bill-payment-bg.jpg", NULL, NULL, NULL, NULL, "full", "20:5", "cover"), new BoxComponentBuilder(
                                "vertical", array(
                            new TextComponentBuilder("ชื่อ-นามสกุล : {$user['eslip04']}{$user['eslip05']} {$user['eslip06']}"),
                            new TextComponentBuilder("โอนเงินเข้า : {$user['eslip10']} {$user['eslip12']}  เลขบัญชี {$user['eslip13']}", NULL, NULL, NULL, NULL, NULL, true),
                            new BoxComponentBuilder(
                                    "horizontal", array(
                                new TextComponentBuilder("รับ"),
                                new SeparatorComponentBuilder(),
                                new TextComponentBuilder("จ่าย"),
                                new SeparatorComponentBuilder(),
                                new TextComponentBuilder("สุทธิ"),
                                    ), 0, "md"
                            ),
                            new BoxComponentBuilder(
                                    "horizontal", array(
                                new TextComponentBuilder(number_format($user['eslip43'], 2)),
                                new SeparatorComponentBuilder(),
                                new TextComponentBuilder(number_format($user['eslip77'], 2)),
                                new SeparatorComponentBuilder(),
                                new TextComponentBuilder(number_format($user['eslip78'], 2)),
                                    ), 0, "md"
                            ),
                                )), new BoxComponentBuilder(
                                "vertical", array(
                            new TextComponentBuilder("วัน เดือน ปี ที่ออกหนังสือรับรอง (" . Ccomponent::getSlipReport($user['eslip79']) . ')', NULL, NULL, NULL, NULL, NULL, true)
                                )
                                ), NULL
                        );
                        $replyData = new FlexMessageBuilder("สรุปรายการเงินเดือน", $textReplyMessage);
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
// $userData['userId']
// $userData['displayName']
// $userData['pictureUrl']
// $userData['statusMessage']
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
                    case (preg_match('/image|audio|video/', $typeMessage) ? true : false) :
                        $response = $bot->getMessageContent($idMessage);
                        if ($response->isSucceeded()) {
// คำสั่ง getRawBody() ในกรณีนี้ จะได้ข้อมูลส่งกลับมาเป็น binary
// เราสามารถเอาข้อมูลไปบันทึกเป็นไฟล์ได้
                            $dataBinary = $response->getRawBody(); // return binary
// ดึงข้อมูลประเภทของไฟล์ จาก header
                            $fileType = $response->getHeader('Content-Type');
                            switch ($fileType) {
                                case (preg_match('/^image/', $fileType) ? true : false):
                                    list($typeFile, $ext) = explode("/", $fileType);
                                    $ext = ($ext == 'jpeg' || $ext == 'jpg') ? "jpg" : $ext;
                                    $fileNameSave = time() . "." . $ext;
                                    break;
                                case (preg_match('/^audio/', $fileType) ? true : false):
                                    list($typeFile, $ext) = explode("/", $fileType);
                                    $fileNameSave = time() . "." . $ext;
                                    break;
                                case (preg_match('/^video/', $fileType) ? true : false):
                                    list($typeFile, $ext) = explode("/", $fileType);
                                    $fileNameSave = time() . "." . $ext;
                                    break;
                            }
                            $botDataFolder = 'botdata/'; // โฟลเดอร์หลักที่จะบันทึกไฟล์
                            $botDataUserFolder = $botDataFolder . $userID; // มีโฟลเดอร์ด้านในเป็น userId อีกขั้น
                            if (!file_exists($botDataUserFolder)) { // ตรวจสอบถ้ายังไม่มีให้สร้างโฟลเดอร์ userId
                                mkdir($botDataUserFolder, 0777, true);
                            }
// กำหนด path ของไฟล์ที่จะบันทึก
                            $fileFullSavePath = $botDataUserFolder . '/' . $fileNameSave;
                            file_put_contents($fileFullSavePath, $dataBinary); // ทำการบันทึกไฟล์
                            $textReplyMessage = "บันทึกไฟล์เรียบร้อยแล้ว"; // $fileNameSave";
                            $replyData = new TextMessageBuilder($textReplyMessage);
                            break;
                        }
                        $failMessage = json_encode($idMessage . ' ' . $response->getHTTPStatus() . ' ' . $response->getRawBody());
                        $replyData = new TextMessageBuilder($failMessage);
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
