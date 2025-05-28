<?php

/*
 * พัฒนาโดย ศิลา กลั่นแกล้ว สสจ.สุพรรณบุรี
 *
 */

namespace app\modules\survay\components;

use yii;
use yii\base\Widget;
use yii\bootstrap4\Html;
use dosamigos\google\maps\LatLng;
use dosamigos\google\maps\overlays\InfoWindow;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Polygon;

// edofre objects instead of dosamigos
//use edofre\markerclusterer\Map;
//use edofre\markerclusterer\Marker;

class Cwidgetmap extends Widget {

    public $area = 72;
    public $zoom = 8;
    public $height = 300;
    public $strokeColor = '‪#‎FFFFFF‬';
    public $strokeOpacity = 0.5;
    public $strokeWeight = 0.8;
    public $fillColor = '#008000';
    public $fillOpacity = 0.5;
    public $color = ['#7cb5ec', '#1d599d', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];
    public $showHosp = ['pcu', 'hos']; #แสดงหน่วยบริการ

    public function init() {
        parent::init();
    }

    public function run() {

        $request = \Yii::$app->request;
        $this->area = '';
        if (strlen($request->get('area')) == 4) {
            $ampcode = $request->get('area');
        } else {
            $ampcode = '';
        }

        $hospcode = '';

        try {
            $data = \Yii::$app->db_datacenter->createCommand("SELECT CONCAT(provcode,distcode) as tt,hoscode,hostype,provcode,distcode,subdistcode FROM chospital WHERE hoscode = '{$hospcode}'")->cache(3600)->queryOne();
        } catch (\Exception $exc) {
            $return = "";
        }
        if ($data['hostype'] <> '01') {
            $ampcode = $data['tt'];
        }

//ระบุ areacode
        $coordinates = Cwidgetmap::getGeocoder($this->area, $ampcode, $kpi_id);
        $pointHos = Cwidgetmap::getGeocoderByHoscode($this->area, $ampcode);
        /*
          echo '<pre>';
          echo $kpi_id;
          print_r($data);
          echo '</pre>';
          exit;
         */
        if (count($coordinates) > 0) {
            $array['x'] = [];
            $array['y'] = [];
//หาค่า Center ของแผนที่
            foreach ($coordinates['polygon']['p'] as $key => $rows) {
                foreach ($rows as $value) {
                    $array['x'][] = $value[1];
                    $array['y'][] = $value[0];
                }
            }
            $lat = @min($array['x']) + ((@max($array['x']) - @min($array['x'])) / 2);
            $lng = @min($array['y']) + ((@max($array['y']) - @min($array['y'])) / 2);

            $center = new LatLng(['lat' => $lat, 'lng' => $lng]);
            $map = new Map([
                'scrollwheel' => FALSE,
                'center' => $center,
                'width' => '100%',
                'height' => $this->height,
                'zoom' => (strlen($request->get('area')) == 4 ? $this->zoom + 2 : $this->zoom),
            ]);
            foreach ($coordinates['polygon']['p'] as $key => $rows) {
                $coords = [];
                foreach ($rows as $value) {
                    $coords[] = new LatLng(['lat' => $value[1], 'lng' => $value[0]]);
                }
                $polygon = new Polygon([
                    'paths' => $coords,
                    'strokeColor' => $this->strokeColor,
                    'strokeOpacity' => $this->strokeOpacity,
                    'strokeWeight' => $this->strokeWeight,
                    'fillColor' => $this->fillColor, //$this->color[rand(0, 9)],
                    //'fillColor' => @($coordinates['polygon']['point'][$key] == 1 ? 'green' : 'red'),
                    'fillOpacity' => $this->fillOpacity
                ]);
// Add a shared info window
                $polygon->attachInfoWindow(new InfoWindow([
                            'content' => '<p>' . $coordinates['polygon']['areaname'][$key] . '</p>'
                            . '<p>' . Html::a('ดูระดับสถานบริการ', ['index', 'kpi_id' => $kpi_id, 'area' => $coordinates['polygon']['areacode'][$key]]) . '</p>'
                ]));
                $map->addOverlay($polygon);
            }
            $icon_green = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
            $icon_red = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            $icon = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Marker-Inside-Chartreuse.png";
            $icon = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Board-Chartreuse.png";
            $icon = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/48/Map-Marker-Marker-Inside-Chartreuse.png";
            $home = "https://cdn1.iconfinder.com/data/icons/flat-artistic-shopping-icons/32/home-20.png";
            $hos = "http://findicons.com/files/icons/186/perfect_city/48/hospital.png";
            $pcu = "http://findicons.com/files/icons/183/gis_gps_map/32/hospital.png";
            foreach ($pointHos as $key => $row) {
                if (in_array($row['hostype'], $this->showHosp) || strlen($request->get('area')) == 4) {
                    $coord = new LatLng(['lat' => $row['lat'], 'lng' => $row['lon']]);
                    $marker = new Marker([
                        'position' => $coord,
                        'title' => $row['hosname'],
                        'icon' => ($row['hostype'] == 'pcu' ? $pcu : $hos),
                    ]);

                    $marker->attachInfoWindow(new InfoWindow([
                                'content' => '<p>' . $row['hosname'] . '</p>'
                    ]));
                    $map->addOverlay($marker);
                }
            }
            return $map->display();
        } else {
            return;
        }
    }

    public static function getGeocoderByHoscode($areacode, $ampcode) {
        try {
            $query = "select * from
                            (select hoscode,hosname,lat,lon,
                            0 as point,
                            0 as kpi,
                            0 as target,
                             IF(hostype IN ('05','06','07'),'hos','pcu') AS hostype
                            from chospital h
                            LEFT JOIN geojson g ON g.hcode = h.hoscode
                            WHERE provcode = '{$areacode}'
                            and hostype in ('03','05','06','07','18')
                            " . (strlen($ampcode) == 4 ? "AND concat(provcode,distcode) = '{$ampcode}'" : "") . "
                            group by h.hoscode ) t1
";

            $data = Yii::$app->db->createCommand($query)->cache(3600)->queryAll();
        } catch (\Exception $e) {
            $data = [];
        }

        return $data;
    }

    public static function getGeocoder($areacode, $ampcode, $kpi) {
        $coordinates = [];
        $polygon['p'] = [];
        $polygon['info'] = [];

        try {
            $query = "SELECT
                            CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            areacode,
                            areatype,
                            geojson,
                            0 as point,
                            0 as kpi,
                            0 as target
                        FROM
                            campur
                                INNER JOIN
                            cchangwat ON cchangwat.changwatcode = campur.changwatcode
                                INNER JOIN
                            geojson ON areacode = ampurcodefull
                                AND areatype = '3'
                                AND LENGTH(geojson) > 0
                                AND cchangwat.changwatcode = '{$areacode}'
                                    " . (strlen($ampcode) == 4 ? "AND campur.ampurcodefull = '{$ampcode}'" : "") . "
                                ";

            $data = Yii::$app->db->createCommand($query)->cache(3600)->queryAll();

            foreach ($data as $key => $rows) {
                $geo = \GuzzleHttp\json_decode($rows['geojson'], true);
                foreach ($geo['coordinates'] as $geo_key => $geo_value) {
                    $polygon['areaname'][] = $rows['areaname'];
                    $polygon['areacode'][] = $rows['areacode'];
                    $polygon['point'][] = $rows['point'];
                    $polygon['kpi'][] = $rows['kpi'];
                    $polygon['target'][] = $rows['target'];
                    array_push($polygon['p'], $geo_value[0]);
                }
            }
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        $coordinates['polygon'] = $polygon;
        return $coordinates;
    }

    public static function getGeocoderVillage2($areacode, $ampcode) {
        try {
            $query = "SELECT
                            CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            ampurcodefull as areacode,
                            village_code,
                            villagename,
                            lat,
                            lng
                        FROM
                            campur
                                INNER JOIN cchangwat ON cchangwat.changwatcode = campur.changwatcode
                                INNER JOIN cvillage  ON cvillage.ampurcode = ampurcodefull
                                LEFT JOIN village_coordinates ON villagecodefull = village_code
                                AND cchangwat.changwatcode = '{$areacode}'
                                    " . (strlen($ampcode) == 4 ? "AND campur.ampurcodefull = '{$ampcode}'" : "") . "
                                #WHERE left(village_code,6) IN (SELECT province FROM person_risk_group)
";

            $data = Yii::$app->db_covid->createCommand($query)->cache(360)->queryAll();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        return $data;
    }

//Wave 2
    public static function getGeocoderVillageWave2($areacode, $ampcode) {

        try {
            $query = "SELECT
                            CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            ampurcodefull as areacode,
                            village_code,
                            villagename,
                            v.lat,
                            v.lng,
                            a.report_type_id as typeid
                        FROM
                            campur
                                INNER JOIN cchangwat ON cchangwat.changwatcode = campur.changwatcode
                                INNER JOIN cvillage  ON cvillage.ampurcode = ampurcodefull
                                LEFT JOIN village_coordinates v ON villagecodefull = village_code
                                INNER JOIN person_risk_wave2 a ON a.province = left(village_code,6)
                                AND cchangwat.changwatcode = '{$areacode}'
								AND date(record_date) >= '2021-04-01'
								AND active_status = 1
                                    " . (strlen($ampcode) == 4 ? "AND campur.ampurcodefull = '{$ampcode}'" : "") . "
                                #WHERE
                                #left(village_code,6) IN (SELECT province FROM person_risk_wave2)
                                group by village_code
";

            $data = Yii::$app->db_covid->createCommand($query)->cache(0)->queryAll();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        return $data;
    }

    public static function getGeocoderVillage($areacode, $ampcode) {

        try {
            $query = "SELECT
                            CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            ampurcodefull as areacode,
                            village_code,

                            villagename,
                            lat,
                            lng
                        FROM
                            campur
                                INNER JOIN cchangwat ON cchangwat.changwatcode = campur.changwatcode
                                INNER JOIN cvillage  ON cvillage.ampurcode = ampurcodefull
                                LEFT JOIN village_coordinates ON villagecodefull = village_code
                                AND cchangwat.changwatcode = '{$areacode}'
                                    " . (strlen($ampcode) == 4 ? "AND campur.ampurcodefull = '{$ampcode}'" : "") . "
                                WHERE
                                left(village_code,6) IN (SELECT province FROM person_risk_group)
";

            $data = Yii::$app->db_covid->createCommand($query)->cache(360)->queryAll();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        return $data;
    }

    public static function getGeoTambon($areacode, $ampcode, $kpi) {

        try {
            $query = "SELECT
                            CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            ampurcodefull as areacode,
                            lat,
                            lng
                        FROM
                            campur
                                INNER JOIN cchangwat ON cchangwat.changwatcode = campur.changwatcode
                                INNER JOIN tambon_gis ON left(TAM_ID,4) = ampurcodefull
                                AND cchangwat.changwatcode = '{$areacode}'
                                    " . (strlen($ampcode) == 4 ? "AND campur.ampurcodefull = '{$ampcode}'" : "") . "
                                ";

            $data = Yii::$app->db_covid->createCommand($query)->cache(3600)->queryAll();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        return $data;
    }

    ///ดึงพิกัดคนไข้ติดเตียง
    public static function getGeocoderPatient($areacode, $ampcode) {

        try {
            $query = "SELECT
                            CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            check_vhid AS areacode,

                            lat,
                            lng
                        FROM  cds_functional a
                            LEFT JOIN campur ON LEFT(check_vhid,4) = campur.ampurcodefull
                                INNER JOIN cchangwat ON cchangwat.changwatcode = campur.changwatcode
                                WHERE a.age_y < 60
";

            $data = Yii::$app->db->createCommand($query)->cache(0)->queryAll();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        return $data;
    }

///ดึงพิกัดคนไข้ติดเตียง
    public static function getGeocoderWorker($areacode, $ampcode) {

        try {
            $query = "SELECT
                            #CONCAT('อ.', ampurname, ' จ.', changwatname) AS areaname,
                            #check_vhid AS areacode,
ampur AS areaname,
business,
                           latitude as  lat,
                           longitude as lng
                        FROM  covid19.regist_worker a

";

            $data = Yii::$app->db->createCommand($query)->queryAll();
        } catch (\Exception $exc) {
            echo $exc->getMessage();
            $data = [];
        }

        return $data;
    }

}
