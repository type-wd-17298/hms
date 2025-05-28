<?php

namespace app\components;

//use app\models\Cdepartment;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\models\ExtProfile;

#use Da\QrCode\QrCode;

class Ccomponent {

    public static function getArrayThaiMonth($var = 0) {
        if ($var == 0) {
            $thaimt = ['ตุลาคม', 'พฤศจิกายน', 'ธันวาคม', 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน'];
        } else {
            $thaimt = ['10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม', '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน'];
        }
        return $thaimt;
    }

    public static function getThaiMonth($month, $rtype = 'S') {
        if ($rtype == 'S')
            $thaimt = array('00' => '', '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', "06" => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.');
        else
            $thaimt = array('00' => '', '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');
        if (strlen($month) == 1)
            $month = str_pad($month, 2, '0', STR_PAD_LEFT);
        return @$thaimt[$month];
    }

    public static function getTokenUser($role) {
        $ids = \Yii::$app->authManager->getUserIdsByRole($role);
        $token = [];
        $data = @ExtProfile::find()->where(['IN', 'user_id', $ids])->cache(600)->all();
        foreach ($data as $value) {
            if (!empty($value->emp->employee_linetoken) && $value->emp->employee_status == 1)
                $token[] = $value->emp;
        }
        return $token;
    }

    public static function Emp($cid) {
        return @Employee::find()->where(['employee_cid' => $cid])->cache(3600)->one();
    }

    public static function DepParent($id) {
        return @EmployeeDep::find()->where(['employee_dep_id' => $id])->cache(3600)->one()->employee_dep_parent;
    }

    public static function Dep($id) {
        return @EmployeeDep::find()->where(['employee_dep_id' => $id])->cache(3600)->one()->employee_dep_label;
    }

    public static function ThaiMonthFlip($string) {
        $thaimtS = ['00' => '', '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', "06" => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.'];
        $flipped = array_flip($thaimtS);
        return $flipped[$string];
    }

    public static function distance($lat1, $lon1, $lat2, $lon2, $unit) {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);

            if ($unit == 'K') {
                return ($miles * 1.609344);
            } else if ($unit == 'N') {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
        /*
          echo distance(32.9697, -96.80322, 29.46786, -98.53506, "M") . " Miles<br>";
          echo distance(32.9697, -96.80322, 29.46786, -98.53506, "K") . " Kilometers<br>";
          echo distance(32.9697, -96.80322, 29.46786, -98.53506, "N") . " Nautical Miles<br>";
         */
    }

    public static function getAddress($code) {
        $query = "SELECT
CONCAT('ต.',a.name_th,' ','อ.',b.name_th,' ','จ.',c.name_th) AS fulladdress
FROM districts a
INNER JOIN amphures b ON b.code = a.amphure_code
INNER JOIN provinces c ON c.id = b.province_id
WHERE a.id = '{$code}'
            ";
        $data = \Yii::$app->db->createCommand($query)->cache(3600)->queryOne();
        return $data['fulladdress'];
    }

    /*
      public static function QrCode($message) {
      if ($message == '')
      $message = date('Y-m-d');
      $qrCode = (new QrCode($message))
      ->setSize(250)
      ->setMargin(5)
      ->useForegroundColor(0, 0, 0);
      $qrCode->writeFile(__DIR__ . '/code.png'); // writer defaults to PNG when none is specified
      return $qrCode->writeDataUri();
      }
     */

    //วันที่
    public static function getThaiDate($scode, $rtype = 'S', $showtime = '') {
        /*
          if ($scode == '0000-00-00') {
          return '-';
          }
         */

        $thaimtS = array('00' => '', '01' => 'ม.ค.', '02' => 'ก.พ.', '03' => 'มี.ค.', '04' => 'เม.ย.', '05' => 'พ.ค.', "06" => 'มิ.ย.', '07' => 'ก.ค.', '08' => 'ส.ค.', '09' => 'ก.ย.', '10' => 'ต.ค.', '11' => 'พ.ย.', '12' => 'ธ.ค.');
        $thaimtL = array('00' => '', '01' => 'มกราคม', '02' => 'กุมภาพันธ์', '03' => 'มีนาคม', '04' => 'เมษายน', '05' => 'พฤษภาคม', '06' => 'มิถุนายน', '07' => 'กรกฎาคม', '08' => 'สิงหาคม', '09' => 'กันยายน', '10' => 'ตุลาคม', '11' => 'พฤศจิกายน', '12' => 'ธันวาคม');

        if (empty($scode)) {
            return '';
        }
        $day = (integer) substr($scode, 8, 2);
        $mt = substr($scode, 5, 2);
        $time = substr($scode, 10, 12);
        $year = (integer) substr($scode, 0, 4) + 543;
        if (strtoupper($rtype) == 'T') {
            return $time;
        }


        if (strtoupper($rtype) == 'L') {
            $tmt = $thaimtL;
            return $day . ' ' . $tmt[$mt] . ' ' . $year . ($showtime <> '' ? ' : ' . $time : '');
        } else {
            $tmt = $thaimtS;
            return $day . ' ' . $tmt[$mt] . ' ' . substr($year, 2, 4) . ' ' . ($showtime <> '' ? $time : '');
        }
    }

//แปลงปี พ.ศ เป็น ค.ศ.
    public static function FnDate543($date) {
        $srt[0] = substr($date, 0, 4);
        $srt[1] = substr($date, 4, 2);
        $srt[2] = substr($date, 6, 2);

        return ($srt[0] - 543) . "-" . $srt[1] . "-" . $srt[2];
    }

//เลขบัตรประชาชน format
    public static function FnID($cid) {
        $srt[0] = substr($cid, 0, 1);
        $srt[1] = substr($cid, 1, 4);
        $srt[2] = substr($cid, 5, 5);
        $srt[3] = substr($cid, 10, 2);
        $srt[4] = substr($cid, 12, 1);
        return $srt[0] . "-" . $srt[1] . "-" . $srt[2] . "-" . $srt[3] . "-" . $srt[4];
    }

//เลขบัตรประชาชน format
    public static function FnIDX($cid) {
        $srt[0] = substr($cid, 0, 1);
        $srt[1] = substr($cid, 1, 4);
        $srt[2] = substr($cid, 5, 5);
        $srt[3] = substr($cid, 10, 2);
        $srt[4] = substr($cid, 12, 1);
        return $srt[0] . "-" . $srt[1] . "-" . 'XXXXX' . "-" . $srt[3] . "-" . $srt[4];
    }

//เบอร์โทรศัพท์ format
    public static function FnMobile($phonenumber) {
        $srt[0] = substr($phonenumber, 0, 3);
        $srt[1] = substr($phonenumber, 3, 3);
        $srt[2] = substr($phonenumber, 6, 4);
        #$srt[3] = substr($phonenumber, 10, 2);
        #$srt[4] = substr($phonenumber, 12, 1);
        return $srt[0] . "-" . $srt[1] . "-" . $srt[2];
    }

    public static function convert($number) {
        $txtnum1 = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
        $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
        $number = str_replace(",", "", $number);
        $number = str_replace(" ", "", $number);
        $number = str_replace("บาท", "", $number);
        $number = explode(".", $number);
        if (sizeof($number) > 2) {
            return 'ทศนิยมหลายตัวนะจ๊ะ';
            exit;
        }
        $strlen = strlen($number[0]);
        $convert = '';
        for ($i = 0; $i < $strlen; $i++) {
            $n = substr($number[0], $i, 1);
            if ($n != 0) {
                if ($i == ($strlen - 1) AND $n == 1) {
                    $convert .= 'เอ็ด';
                } elseif ($i == ($strlen - 2) AND $n == 2) {
                    $convert .= 'ยี่';
                } elseif ($i == ($strlen - 2) AND $n == 1) {
                    $convert .= '';
                } else {
                    $convert .= $txtnum1[$n];
                }
                $convert .= $txtnum2[$strlen - $i - 1];
            }
        }

        $convert .= 'บาท';
        if ($number[1] == '0' OR $number[1] == '00' OR
                $number[1] == '') {
            $convert .= 'ถ้วน';
        } else {
            $strlen = strlen($number[1]);
            for ($i = 0; $i < $strlen; $i++) {
                $n = substr($number[1], $i, 1);
                if ($n != 0) {
                    if ($i == ($strlen - 1) AND $n == 1) {
                        $convert .= 'เอ็ด';
                    } elseif ($i == ($strlen - 2) AND
                            $n == 2) {
                        $convert .= 'ยี่';
                    } elseif ($i == ($strlen - 2) AND
                            $n == 1) {
                        $convert .= '';
                    } else {
                        $convert .= $txtnum1[$n];
                    }
                    $convert .= $txtnum2[$strlen - $i - 1];
                }
            }
            $convert .= 'สตางค์';
        }
        return $convert;
    }

    public static function strip_tags_content($string) {
        // ----- remove HTML TAGs -----
        $string = preg_replace('/<[^>]*>/', ' ', $string);
        // ----- remove control characters -----
        $string = str_replace("\r", '', $string);
        //$string = str_replace("\n", ' ', $string);
        $string = str_replace("\t", ' ', $string);
        // ----- remove multiple spaces -----
        $string = trim(preg_replace('/ {2,}/', ' ', $string));
        return $string;
    }

    // remove weekend and holidays
    public static function formatDate($startDate) {
        $holiday = day_holiday();
        $start = date_create_from_format('d-m-Y H:i:s', $startDate . ' 00:00:00');
        do {
            $ts = (int) $start->format('U');
            $dow = (int) $start->format('w');
            if (!in_array($ts, $holiday) && $dow != 0 && $dow != 6)
                break;
            $start->modify('+1 day');
        }
        while (true);
        return $start->format('d-m-Y');
    }

// remove weekend and holidays
    public static function getWeekdayDifference(\DateTime $startDate, \DateTime $endDate) {
        $isWeekday = function (\DateTime $date) {
            return true;
            //return $date->format('N') < 6;
        };

        $days = $isWeekday($endDate) ? 1 : 0; //ตัดเสาร์-อาทิตย์
        while ($startDate->diff($endDate)->days > 0) {
            $days += $isWeekday($startDate) ? 1 : 0;
            $startDate = $startDate->add(new \DateInterval("P1D"));
        }

        return $days;
    }

}
