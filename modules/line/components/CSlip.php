<?php

namespace app\modules\line\components;

use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use app\modules\epayslip\components\Cdata;
use app\modules\epayslip\components\Ccomponent;

class CSlip {

    public static function getList($event, $userData) {

        //เรียกใช้งานข้อมูลเงินเดือน
        $user = Cdata::getStatement($event['source']['userId']);
        if (!isset($user['eslip01'])) {
            $textReplyMessage = 'ระบบไม่พบข้อมูลของ คุณ ' . $userData['displayName'] . ' ค่ะ';
            $replyData = new TextMessageBuilder($textReplyMessage);
        } else {
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
                new TextComponentBuilder("โอนเงินเข้า : {$user['eslip10']} {$user['eslip12']}  เลขบัญชี {$user['eslip13']}", NULL, NULL, NULL, NULL, NULL, TRUE),
                new BoxComponentBuilder(
                        "horizontal", array(
                    new TextComponentBuilder("รับ", null, null, null, null, null, null, null, 'bold'),
                    new SeparatorComponentBuilder(),
                    new TextComponentBuilder("จ่าย", null, null, null, null, null, null, null, 'bold'),
                    new SeparatorComponentBuilder(),
                    new TextComponentBuilder("สุทธิ", null, null, null, null, null, null, null, 'bold'),
                        ), 0, "md"
                ),
                new BoxComponentBuilder(
                        "horizontal", array(
                    new TextComponentBuilder(number_format($user['eslip43'], 2), null, null, null, 'end'),
                    new SeparatorComponentBuilder(),
                    new TextComponentBuilder(number_format($user['eslip77'], 2), null, null, null, 'end'),
                    new SeparatorComponentBuilder(),
                    new TextComponentBuilder(number_format($user['eslip78'], 2), null, null, null, 'end'),
                        ), 0, "md"
                ),
                    )), new BoxComponentBuilder(
                    "vertical", array(
                new TextComponentBuilder("วัน เดือน ปี ที่ออกหนังสือรับรอง (" . Ccomponent::getSlipReport($user['eslip79']) . ')', NULL, NULL, NULL, NULL, NULL, TRUE)
                    )
                    ), NULL
            );
            $replyData = new FlexMessageBuilder("สรุปรายการเงินเดือน", $textReplyMessage);
        }
        return $replyData;
    }

}
