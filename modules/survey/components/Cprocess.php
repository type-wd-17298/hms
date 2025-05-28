<?php

namespace app\modules\survay\components;

#use SoapClient;

//use yii\httpclient\Client;
use yii\bootstrap4\Html;

class Cprocess {

//ประมวลผลค่าปิงปอง 7 สี

    static function calResult($model) {
        try {
            $sqlQuery = "SELECT * FROM ccolor WHERE color_id = '{$model}'";
            $data = \Yii::$app->db->createCommand($sqlQuery)->queryOne(); //->cache(0)
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        $return = @Html::a($data['color_name'], '#',
                        [
                            'class' => 'badge badge-block badge-sm ' . $data['color_class'],
                            'style' => "width:80px;background-color:{$data['color_rgb']}",
                            'data-toggle' => 'tooltip',
                            'title' => $data['color_group'],
        ]);

        return $return;
    }

    static function calResultHT($model) {
        $html = [];
        $html['result'] = 0;
        $chronic = json_decode($model->person->person_chronic, true);

        /*
          กรณีในกลุ่มป่วย DM/HT ต้องอยู่ตั้งแต่สีเขียวเข้มขึ้นไป แม้ว่าผลจะปกติก็ตาม
         */
        /*
          if (in_array($chronic, [1])) {
          $html['message'] = 'ป่วยระดับ 0';
          $html['text-color'] = 'green';
          $html['text-class'] = 'text-white';
          $html['result'] = 3;
          }
         *
         * 164->71
         */

        if ((in_array($model->person_screen_sbp, range(0, 139)) || in_array($model->person_screen_dbp, range(0, 89)))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'green';
            $html['text-class'] = 'text-white';
            $html['result'] = 3;
        }
        if ((in_array($model->person_screen_sbp, range(140, 159)) || in_array($model->person_screen_dbp, range(90, 99)))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'yellow';
            $html['text-class'] = '';
            $html['result'] = 4;
        }
        if ((in_array($model->person_screen_sbp, range(160, 179)) || in_array($model->person_screen_dbp, range(100, 109)))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'orange';
            $html['text-class'] = 'text-white';
            $html['result'] = 5;
        }
        if ((in_array($model->person_screen_sbp, range(180, 599)) || in_array($model->person_screen_dbp, range(110, 599)))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'red';
            $html['text-class'] = 'text-white';
            $html['result'] = 6;
        }
        if (0) {
            $html['message'] = 'โรคแทรกซ้อน';
            $html['text-color'] = 'black';
            $html['text-class'] = 'text-white';
            $html['result'] = 7;
        }


        if (!in_array($chronic, [1]) && ($model->person_screen_sbp < 120 || $model->person_screen_dbp < 80)) {
            $html['message'] = 'กลุ่มปกติ';
            $html['text-color'] = '';
            $html['text-class'] = 'btn-light';
            $html['result'] = 1;
        }
        if (!in_array($chronic, [1]) && (in_array($model->person_screen_sbp, range(121, 139)) || in_array($model->person_screen_dbp, range(81, 89)))) {
            $html['message'] = 'กลุ่มเสี่ยง';
            $html['text-color'] = '#00ff00';
            $html['text-class'] = '';
            $html['result'] = 2;
        }

        $html['message'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        #$return = '<div class="input-group input-group-sm"><div class="input-group-append">';
        #$return .= Html::a('<i class="fa-solid fa-tags fa-lg"></i>', '#', ['class' => 'btn btn-light active']);
        $return = @Html::a($html['message'], '#', ['class' => 'badge badge-block badge-sm ' . $html['text-class'], 'style' => 'background-color:' . $html['text-color']]);
        #$return .= '</div></div>';
        return $return;
    }

//ประมวลผลค่าปิงปอง 7 สี
    static function calResultDM($model) {
        $html = [];
        $html['result'] = 0;
        $chronic = json_decode($model->person->person_chronic, true);
        //--------------------------------------------------------------------------------

        if (in_array($model->person_screen_fbs, range(0, 125))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'green';
            $html['text-class'] = 'text-white';
            $html['result'] = 3;
        }
        if (in_array($model->person_screen_fbs, range(126, 154))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'yellow';
            $html['text-class'] = '';
            $html['result'] = 4;
        }
        if (in_array($model->person_screen_fbs, range(155, 182))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'orange';
            $html['text-class'] = 'text-white';
            $html['result'] = 5;
        }
        if (in_array($model->person_screen_fbs, range(183, 599))) {
            $html['message'] = 'กลุ่มป่วย';
            $html['text-color'] = 'red';
            $html['text-class'] = 'text-white';
            $html['result'] = 6;
        }
        if (0) {
            $html['message'] = 'โรคแทรกซ้อน';
            $html['text-color'] = 'black';
            $html['text-class'] = 'text-white';
            $html['result'] = 7;
        }

        if (!in_array($chronic, [1]) && $model->person_screen_fbs < 100) {
            $html['message'] = 'กลุ่มปกติ';
            $html['text-color'] = '';
            $html['text-class'] = 'btn-light';
            $html['result'] = 1;
        }
        if (!in_array($chronic, [1]) && in_array($model->person_screen_fbs, range(100, 125))) {
            $html['message'] = 'กลุ่มเสี่ยง';
            $html['text-color'] = '#00ff00';
            $html['text-class'] = '';
            $html['result'] = 2;
        }

        $html['message'] = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
        #$return = '<div class="input-group input-group-sm"><div class="input-group-append">';
        #$return .= Html::a('<i class="fa-solid fa-tags fa-lg"></i>', '#', ['class' => 'btn btn-light active']);
        $return = @Html::a($html['message'], '#', ['class' => 'badge badge-block badge-sm ' . $html['text-class'], 'style' => 'background-color:' . $html['text-color']]);
        #$return .= '</div></div>';
        return $return;
    }

}
