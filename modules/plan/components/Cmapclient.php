<?php

/*
 * พัฒนาโดย ศิลา กลั่นแกล้ว สสจ.สุพรรณบุรี
 *
 */

namespace app\modules\survay\components;

use yii\base\Widget;
#use yii\helpers\Html;
#use app\components\Cprocess;
#use yii\web\JsExpression;
use dosamigos\google\maps\LatLng;
#use dosamigos\google\maps\services\DirectionsWayPoint;
#use dosamigos\google\maps\services\TravelMode;
#use dosamigos\google\maps\overlays\PolylineOptions;
#use dosamigos\google\maps\services\DirectionsRenderer;
#use dosamigos\google\maps\services\DirectionsService;
use dosamigos\google\maps\overlays\InfoWindow;
#use dosamigos\google\maps\services\DirectionsRequest;
use dosamigos\google\maps\overlays\Polygon;
use dosamigos\google\maps\Map;
use dosamigos\google\maps\overlays\Marker;
use dosamigos\google\maps\overlays\Icon;
use dosamigos\google\maps\layers\HeatmapLayer; //ทำเอง
use dosamigos\google\maps\Point;

//use edofre\markerclusterer\Map; // AS MapCluster;
//use edofre\markerclusterer\Marker; // AS MarkerCluster;

class Cmapclient extends Widget {

    public $area = 72;
    public $zoom = 8;
    public $height = 300;
    public $strokeColor = '‪#‎FFFFFF‬';
    public $strokeOpacity = 0.3;
    public $strokeWeight = 2;
    public $fillColor = '#FFE4B5';
    public $fillOpacity = 0.3;
    public $color = ['#7cb5ec', '#1d599d', '#90ed7d', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];
    public $point = [];
    public $condition = '';
    public $content = '';
    public $icon = '';

    public function init() {
        parent::init();
    }

    public function run() {
        $request = \Yii::$app->request;
        $area = '';
        $ampcode = '';
        $coordinates = [1]; //Cwidgetmap::getGeocoder($area, $ampcode, '');

        if (count($coordinates) > 0 || 1) {
            $array['x'] = [];
            $array['y'] = [];

//หาค่า Center ของแผนที่
            $heatmapPoint = [];
            foreach ($this->point as $key => $rows) {
                if (!empty($rows['lat']) && !empty($rows['lng']) && (double) $rows['lat'] >= 0 && (double) $rows['lng'] >= 0 && ((double) ($rows['lat'] + 20) < (double) $rows['lng'])) {
                    #echo (double) $rows['lat'] . '--------------' . (double) $rows['lng'] . '<br>';

                    if ((double) $rows['lat'] > (double) $rows['lng']) {
                        $lat = $rows['lng'];
                        $lng = $rows['lat'];
                    } else {
                        $lat = $rows['lat'];
                        $lng = $rows['lng'];
                    }

                    $array['x'][] = (double) $lat;
                    $array['y'][] = (double) $lng;

                    $heatmapPoint[] = new LatLng(['lat' => $lat, 'lng' => $lng]);
                }
            }

            /*
              foreach ($coordinates['polygon']['p'] as $key => $rows) {
              foreach ($rows as $value) {
              $array['x'][] = $value[1];
              $array['y'][] = $value[0];
              }
              }

             */
            $lat = @min($array['x']) + ((@max($array['x']) - @min($array['x'])) / 2);
            $lng = @min($array['y']) + ((@max($array['y']) - @min($array['y'])) / 2);
            $center = new LatLng(['lat' => $lat, 'lng' => $lng]);

            $map = new Map([
                #'scrollwheel' => FALSE,
                'center' => $center,
                'scrollwheel' => false,
                //'maxZoom' => 12,
                'zoomControl' => true,
                'draggable' => true,
                'width' => '100%',
                'height' => $this->height,
                'zoom' => $this->zoom,
                'tilt' => 45,
                    //'clusterOptions' => [
                    //'minimumClusterSize' => 12,
                    //'gridSize' => 1,
                    // ],
            ]);
            /*
              $Heatmap = new HeatmapLayer([
              'data' => $heatmapPoint,
              'map' => $map
              ]);
              $map->addOverlay($Heatmap);
             */
            /*
              foreach ($coordinates['polygon']['p'] as $key => $rows) {
              $coords = [];
              foreach ($rows as $value) {
              $coords[] = new LatLng(['lat' => (double) $value[1], 'lng' => (double) $value[0]]);
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
              //. '<p>' . Html::a('ดูระดับสถานบริการ', ['index', 'kpi_id' => $kpi_id, 'area' => $coordinates['polygon']['areacode'][$key]]) . '</p>'
              ]));
              $map->addOverlay($polygon);
              }
             */

            $icon_green = "http://maps.google.com/mapfiles/ms/icons/green-dot.png";
            #$icon_red = "http://maps.google.com/mapfiles/ms/icons/red-dot.png";
            $icon_red = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/32/Map-Marker-Board-Pink.png";
            //$icon_green = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/32/Map-Marker-Board-Chartreuse.png";
            $icon_info = "https://cdn1.iconfinder.com/data/icons/Map-Markers-Icons-Demo-PNG/32/Map-Marker-Board-Azure.png";
            $svgMarker = "M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z";
//            const svgMarker = {
//                path: "M10.453 14.016l6.563-6.609-1.406-1.406-5.156 5.203-2.063-2.109-1.406 1.406zM12 2.016q2.906 0 4.945 2.039t2.039 4.945q0 1.453-0.727 3.328t-1.758 3.516-2.039 3.070-1.711 2.273l-0.75 0.797q-0.281-0.328-0.75-0.867t-1.688-2.156-2.133-3.141-1.664-3.445-0.75-3.375q0-2.906 2.039-4.945t4.945-2.039z",
//                fillColor: "blue",
//                fillOpacity: 0.6,
//                strokeWeight: 0,
//                rotation: 0,
//                scale: 2,
//                anchor: new google.maps.Point(15, 30),
//            };



            foreach ($this->point as $key => $row) {

                if (!empty($row['lat']) && !empty($row['lng']) && (double) $row['lat'] >= 0 && (double) $row['lng'] >= 0 && ((double) ($row['lat'] + 20) < (double) $row['lng'])) {

                    if ((double) $row['lat'] > (double) $row['lng']) {
                        $lat = (double) $row['lng'];
                        $lng = (double) $row['lat'];
                    } else {
                        $lat = (double) $row['lat'];
                        $lng = (double) $row['lng'];
                    }

                    $array['x'][] = (double) $lat;
                    $array['y'][] = (double) $lng;

                    $coord = new LatLng(['lat' => $lat, 'lng' => $lng]);

//                    $url = 'data:image/svg+xml;utf-8,<svg width="24" height="24" xmlns="http://www.w3.org/2000/svg">' .
//                            '<rect stroke="black" fill="' . $row['color_code'] . '" x="1" y="1" width="20" height="20" />' .
//                            '<text x="12" y="18" font-size="12pt" font-family="Arial" font-weight="bold" ' .
//                            'text-anchor="middle" fill="" >P</text>' .
//                            '</circle>' .
//                            '</svg>';

                    if (isset($row['color_code']) && $row['color_code'] <> '') {

                        $seq = substr($row['color_id'], 2, 1);
                        //if($seq)

                        $color = str_replace('#', '%23', $row['color_rgb']);
                        $url = 'data:image/svg+xml;utf-8,<svg width="30" height="30" xmlns="http://www.w3.org/2000/svg">' .
                                '<circle cx="15" cy="15" r="10" style="fill:' . $color . ';stroke-width:3;stroke:rgb(0,0,0);stroke-opacity:0.5;opacity:0.8">' .
                                //'<animate attributeName="opacity" dur="1s" values="0;1;0" repeatCount="indefinite" begin="0.1" />' .
                                '</circle>' .
                                '</svg>';
                        $icon = $url;
                    } else {
                        $icon = $icon_red;
                    }

                    if ($this->icon)
                        $icon = $this->icon;

                    if (is_array($this->icon))
                        $icon = $this->icon['mapping'][$row[$this->icon['condition']]];

                    $marker = new Marker([
                        'position' => $coord,
                        'title' => '',
                        'icon' => $icon
                    ]);

                    foreach ($this->point[0] as $key => $rows) {
                        if ($key == 0) {
                            $mapKeyword['{' . $key . '}'] = $rows;
                        }
                    }

                    $keysearch = [];
                    $keymap = [];

                    foreach ($mapKeyword as $key => $value) {
                        $keysearch[] = $key;
                        $keymap[] = $value;
                    }

                    $string = str_replace($keysearch, $keymap, $this->content);
                    $ref = ''; #\yii\helpers\Html::a('<span class="btn btn-primary btn-xs"><span class="glyphicon glyphicon-globe" aria-hidden="true"></span> แสดงแผนที่</span>', ['/wmgis/default/map', 'qa_search' => 'H' . \Yii::$app->user->identity->profile->hospcode, 'qb_search' => $row['cid']], ['target' => '_blank', 'class' => 'linksWithTarget']);

                    $marker->attachInfoWindow(new InfoWindow([
                                'content' => '<b>' . $row['person_name'] . '</b><br>บ้านเลขที่ ' . $row['address_name'] . $string . '<br>' . $ref
                    ]));

                    $map->addOverlay($marker);
                }
            }
            return $map->display();
        } else {
            return;
        }
    }

}
