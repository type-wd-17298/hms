<?php

namespace app\modules\line\components;

//use yii\base\Component;
//use \LINE\LINEBot;
//use \LINE\LINEBot\HTTPClient;
//use \LINE\LINEBot\HTTPClient\CurlHTTPClient;
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
use app\modules\hr\components\Cdata;
use app\modules\hr\components\Ccomponent;

#use app\modules\hr\models\AttendanceTime;

class CAttendance {

    public static function getList($event, $userData, $mm = '') {

//เรียกใช้งานข้อมูลเข้าออกงาน
        try {
            $data = Cdata::getData($event['source']['userId'], $mm);

            if (!isset($data[0]['dateofmonth'])) {
                $textReplyMessage = 'ระบบไม่พบข้อมูลของ คุณ ' . @$userData['displayName'] . ' ค่ะ';
                $replyData = new TextMessageBuilder($textReplyMessage);
            } else {
                $builder = [
                    new TextComponentBuilder("ชื่อ-นามสกุล : " . $data[0]['employee_name']),
                    new TextComponentBuilder("หน่วยงาน : " . $data[0]['department_name'], NULL, NULL, NULL, NULL, NULL, TRUE),
                    new BoxComponentBuilder(
                            "horizontal", array(
                        new TextComponentBuilder("วันที่", null, null, null, 'end', null, null, null, 'bold'),
                        new SeparatorComponentBuilder(),
                        new TextComponentBuilder("เข้า", null, null, null, 'end', null, null, null, 'bold'),
                        new SeparatorComponentBuilder(),
                        new TextComponentBuilder("ออก", null, null, null, 'end', null, null, null, 'bold'),
                        new SeparatorComponentBuilder(),
                        new TextComponentBuilder("หมายเหตุ", null, null, null, 'end', null, null, null, 'bold'),
                            ), 0, "xs"
                    ),
                ];


                foreach ($data as $row) {

                    $dd = date('w', strtotime($row['dateofmonth']));
                    if (!in_array($dd, [0, 6])) {//ตัวเสาร์-อาทิตย์
                        $builder[] = new BoxComponentBuilder("horizontal", [
                            new TextComponentBuilder($row['dayofmonth'], null, null, null, 'end'),
                            new SeparatorComponentBuilder(),
                            new TextComponentBuilder(($row['scanin2'] <> '0000-00-00 00:00:00' ? Ccomponent::getThaiDate($row['scanin2'], 'TS') : '-'), null, null, null, 'end'),
                            new SeparatorComponentBuilder(),
                            new TextComponentBuilder(($row['scanout2'] <> '0000-00-00 00:00:00' ? Ccomponent::getThaiDate($row['scanout2'], 'TS') : '-'), null, null, null, 'end'),
                            new SeparatorComponentBuilder(),
                            new TextComponentBuilder(($row['attime_message'] <> '' ? $row['attime_message'] : '-'), null, null, null, 'end'),
                                ]
                                , 0, "xs"
                        );
                    }
                }

                $textReplyMessage = new BubbleContainerBuilder(
                        "ltr", // กำหนด NULL หรือ "ltr" หรือ "rtl"
                        new BoxComponentBuilder(
                        "vertical", array(
                    new TextComponentBuilder("สรุปการลงชื่อปฏิบัติงานเดือน", NULL, NULL, "md", "center"),
                    new TextComponentBuilder(Ccomponent::getThaiDate($data[0]['dateofmonth'], 'S'), NULL, NULL, "xl", "center")
                        )
                        ), new ImageComponentBuilder(
                        "https://mobidev.biz/wp-content/uploads/2020/01/multimodal-biometrics-recognition-identification-1-2048x939.png", NULL, NULL, NULL, NULL, "full", "20:5", "cover"), new BoxComponentBuilder(
                        "vertical", $builder
                        )
                        , new BoxComponentBuilder(
                        "vertical", array(
                    new TextComponentBuilder("-", NULL, NULL, NULL, NULL, NULL, TRUE)
                        )
                        ), NULL
                );

                //$replyData = new TextMessageBuilder("TEST");
                $replyData = new FlexMessageBuilder("สรุปการลงชื่อปฏิบัติงาน", $textReplyMessage);
            }

            // $replyData = new TextMessageBuilder("TEST");
        } catch (\Exception $exc) {

            $replyData = new TextMessageBuilder($exc->getMessage());
        }


        return $replyData;
    }

}
