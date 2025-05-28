<?php

namespace app\modules\office\models;

use Yii;

class BookMeetingRoom extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'book_meetingroom';
    }

    /*
      public function rules() {
      return [
      [['leave_type_detail'], 'string'],
      [['leave_type_active'], 'integer'],
      [['leave_type_name'], 'string', 'max' => 100],
      [['leave_type_form'], 'string', 'max' => 50],
      ];
      }
     */

    public function attributeLabels() {
        return [
            'bk_meetingroom_id' => 'Leave Type ID',
            'bk_meetingroom_name' => 'ประเภทการลา',
            'bk_meetingroom_status' => 'Leave Type Detail',
            'bk_meetingroom_sort' => 'Leave Type Form',
            'bk_meetingroom_color' => 'Leave Type Active',
        ];
    }

}
