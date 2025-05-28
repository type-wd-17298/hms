<?php

namespace app\modules\line\components;

use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use app\modules\hr\components\Cdata;
use app\modules\hr\components\Ccomponent;

class CBeacon {

    public static function build($userData, $device) {
        //เรียกใช้งานข้อมูลเงินเดือน
        $user = Cdata::getTimestamp($userData['userId'], $device);
        $textReplyMessage = new BubbleContainerBuilder(
                "ltr", // กำหนด NULL หรือ "ltr" หรือ "rtl"
                new BoxComponentBuilder(
                        "vertical", array(
                    new TextComponentBuilder("ยินดีต้อนรับเข้าสู่", NULL, NULL, "md", "center", null, null, null, 'bold'),
                    new TextComponentBuilder("สำนักงานสาธารณสุขจังหวัดสุพรรณบุรี", NULL, NULL, "md", "center"),
                        //new TextComponentBuilder(Ccomponent::getThaiDate(date('Y-m-d H:i:s'), 'L', 1), NULL, NULL, "xl", "center")
                        )
                ), new ImageComponentBuilder(
                        "https://i.pinimg.com/originals/09/33/26/093326a8f5f6d0bd6f9c816fdc799b10.jpg", NULL, NULL, NULL, NULL, "full", "20:5", "cover")
                , new BoxComponentBuilder(
                        "vertical", array(
                    new ImageComponentBuilder(
                            $userData['pictureUrl'], NULL, NULL, NULL, NULL, "md", "5:5", "cover"),
                    new TextComponentBuilder("ชื่อ-นามสกุล : {$user['employee_name']}"),
                    new TextComponentBuilder($user['dep'], NULL, NULL, NULL, NULL, NULL, TRUE),
                    #new SeparatorComponentBuilder(),
                    new TextComponentBuilder("สถานที่ลงเวลา  : {$user['device_name']}", NULL, NULL, NULL, NULL, NULL, TRUE),
                    new BoxComponentBuilder(
                            "vertical", array(
                        new TextComponentBuilder("เวลาที่บันทึก", NULL, NULL, "md", "center", null, null, null, 'bold'),
                        new TextComponentBuilder(Ccomponent::getThaiDate($user['scandate'], 'S', 1), NULL, NULL, "xl", "center", null, null, null, 'bold', '#229954'),
                        new SeparatorComponentBuilder(),
                        new TextComponentBuilder("ขอบคุณค่ะ ;)", NULL, NULL, "sm", "center", null, null, null),
                            )
                    )
                        )), new BoxComponentBuilder(
                        "vertical", array(
                        //new TextComponentBuilder('ขอบคุณค่ะ ;)', NULL, NULL, NULL, NULL, NULL, TRUE)
                        )
                ),
                NULL
        );
        $replyData = new FlexMessageBuilder("รายงานการเข้า-ออกปฏิบัติงาน", $textReplyMessage);
        return $replyData;
    }

}
