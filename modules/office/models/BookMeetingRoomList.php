<?php

namespace app\modules\office\models;

use Yii;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;

class BookMeetingRoomList extends \yii\db\ActiveRecord {

    public static function tableName() {
        return 'book_meetingroom_list';
    }

    public function rules() {
        return [
            [['bk_meetingroom_id', 'date_event_timein', 'date_event_timeout', 'subject', 'bk_number_attendee'], 'required'],
            [['subject', 'detail'], 'string'],
            [['bk_meetingroom_id', 'bk_number_attendee', 'employee_dep_id'], 'integer'],
            ['date_event_timein', 'validateDulFunds'],
        ];
    }

    public function validateDulFunds($attribute, $params, $validator) {
        if ($this->isNewRecord) {
            $modelBook = BookMeetingRoomList::find()
                    ->where(['bk_meetingroom_id' => $this->bk_meetingroom_id])
                    ->andWhere(['OR',
                        new \yii\db\Expression("date_event_timein BETWEEN '{$this->date_event_timein}' AND '{$this->date_event_timeout}' "),
                        new \yii\db\Expression("date_event_timeout BETWEEN '{$this->date_event_timein}' AND '{$this->date_event_timeout}' "),
                        new \yii\db\Expression("'{$this->date_event_timein}' BETWEEN date_event_timein AND date_event_timeout"),
                        new \yii\db\Expression("'{$this->date_event_timeout}' BETWEEN date_event_timein AND date_event_timeout"),
                    ])
                    ->count();
            if ($modelBook > 0) {
                $this->addError('date_event_timein', 'ไม่สามารถบันทึกการจองของคุณ เลือกช่วงเวลาซ้ำซ้อน. กรุณาเลือกช่วงเวลาอื่น');
            }
        }
        if ($this->date_event_timein > $this->date_event_timeout) {
            $this->addError('date_event_timeout', 'คุณเลือกช่วงเวลาไม่ถูกต้อง. ');
        }
    }

    public function attributeLabels() {
        return [
            'id' => 'ID',
            'year' => 'year',
            'bk_meetingroom_id' => 'ห้องประชุม',
            'date_event' => 'date_event',
            'timein' => 'timein',
            'timeout' => 'timeout',
            'subject' => 'ประชุมเรื่อง',
            'detail' => 'รายละเอียด',
            'employee_id' => 'เจ้าหน้าที่',
            'employee_dep_id' => 'หน่วยงาน',
            'date_event_timein' => 'เวลาเริ่มการประชุม',
            'date_event_timeout' => 'เวลาสิ้นสุดการประชุม',
            'bk_number_attendee' => 'จำนวนผู้เข้าร่วมประชุม'
        ];
    }

    public function getRooms() {
        return $this->hasOne(BookMeetingRoom::className(), ['bk_meetingroom_id' => 'bk_meetingroom_id']);
    }

    public function getEmp() {
        return $this->hasOne(Employee::className(), ['employee_id' => 'employee_id']);
    }

    public function getDep() {
        return $this->hasOne(EmployeeDep::className(), ['employee_dep_id' => 'employee_dep_id']);
    }

}
