<?php

namespace app\modules\servicedesk\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\components\Ccomponent;

class DefaultController extends Controller {

    public function actionIndex() {
        $chart = [];
        $chartData = [];
        $series = [];
        $Query = "SELECT
                            COUNT(*) AS cc,
                            COUNT(IF(a.service_status_id = 1,1,NULL)) AS cc_wait,
                            a.service_status_id,
                            service_status_name,
                            a.service_problem_id,
                            service_problem_name
                            FROM service_list a
                            LEFT JOIN service_status b ON a.service_status_id = b.service_status_id
                            LEFT JOIN service_problem c ON a.service_problem_id = c.service_problem_id
                            -- WHERE date(service_list_date) = current_date()
                            GROUP BY a.service_problem_id ";

        $Query = "SELECT service_problem_name,a.service_problem_id,COUNT(*) AS cc
FROM `service_list` a
LEFT JOIN `service_problem` b ON a.service_problem_id = b.`service_problem_id`
GROUP BY a.service_problem_id -- service_list_date
ORDER BY cc DESC
";

        $rows = \Yii::$app->db_servicedesk->createCommand($Query)->queryAll();
        $value = [];
        foreach ($rows as $value) {
            @$chartData['data'][] = [
                'name' => $value['service_problem_name'],
                'y' => (double) $value['cc']];
        }

        //@sort($chartData['data']);
        $chart[0]['name'] = 'บริการ';
        $chart[0]['groupPadding'] = 0;
        $chart[0]['colorByPoint'] = true;
        $chart[0]['data'] = $chartData['data'];

        return $this->render('index', [
                    'chart' => $chart,
                    'chart2' => $this->getDatatrand(),
        ]);
    }

    public function getDatatrand() {
        try {
            $query = "SELECT
COUNT(*) AS cc
,DATE(service_list_date) AS dd
FROM service_list a
WHERE service_list_date <= CURRENT_DATE()
AND service_status_id = 5
GROUP BY DATE(service_list_date)
ORDER BY service_list_date DESC
                        ";
            $result = \Yii::$app->db_servicedesk->createCommand($query)->queryAll();
        } catch (\Exception $e) {
            $result = [];
        }
        $chart = [];
        $series = [];
        foreach ($result as $value) {
            $series[] = ['name' => Ccomponent::getThaiDate($value['dd'], 'S'), 'y' => (double) $value['cc']];
        }
        $chart = [
            [
                'name' => 'จำนวนครั้งที่ให้บริการ',
                'data' => $series,
                'baseSeries' => 1,
            ],
        ];

        return $chart;
    }

    public function actionReport() {
        $query = "
SELECT
*,
ROUND((ff*100)/cc,2) AS pp
FROM( SELECT
service_list_date
,service_problem_name
,service_list_date_finish
,service_problem_sla
,COUNT(*) AS cc
,MAX(TIMESTAMPDIFF(MINUTE,service_list_date,service_list_date_finish)) AS ccmax
,MIN(TIMESTAMPDIFF(MINUTE,service_list_date,service_list_date_finish)) AS ccmin
,AVG(TIMESTAMPDIFF(MINUTE,service_list_date,service_list_date_finish)) AS ccavg
,(TIMESTAMPDIFF(MINUTE,service_list_date,service_list_date_finish)) AS dd
,COUNT(IF((TIMESTAMPDIFF(MINUTE,service_list_date,service_list_date_finish)) <= service_problem_sla,1,NULL)) AS ff
FROM service_problem b
LEFT JOIN service_list a ON a.service_problem_id = b.service_problem_id AND service_status_id = 5
WHERE 1
GROUP BY b.service_problem_id
ORDER BY service_problem_name ASC) AS tt
";
        $result = \Yii::$app->db_servicedesk->createCommand($query)->queryAll();
        $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => $attributes,
            ],
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);
        return $this->render('report', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionIncidence() {
        $query = "
SELECT
service_list_date
,service_problem_name
,service_list_date_finish
,service_problem_sla
,COUNT(*) AS cc

FROM service_problem b
LEFT JOIN service_list a ON a.service_problem_id = b.service_problem_id AND service_status_id = 5
WHERE 1
GROUP BY b.service_problem_id
ORDER BY cc DESC
";
        $result = \Yii::$app->db_servicedesk->createCommand($query)->queryAll();
        $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
        $dataProvider = new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => $attributes,
            ],
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        $query = "
SELECT
a.department_id
,department_name
,COUNT(*) AS cc
FROM service_list a
INNER JOIN hms_product_db.cdepartment b ON a.department_id = b.department_id
WHERE service_status_id = 5
GROUP BY a.department_id
HAVING  cc > 5
ORDER BY cc DESC
";
        $result = \Yii::$app->db_servicedesk->createCommand($query)->queryAll();
        $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
        $dataProvider2 = new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => $attributes,
            ],
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        $query = "
SELECT
a.employee_id
,employee_fullname
,COUNT(*) AS cc
FROM service_list a
INNER JOIN hms_product_db.employee b ON a.employee_id= b.employee_id
WHERE service_status_id = 5
-- AND a.employee_id <> a.employee_id_operation
GROUP BY a.employee_id
HAVING  cc > 5
ORDER BY cc DESC
";
        $result = \Yii::$app->db_servicedesk->createCommand($query)->queryAll();
        $attributes = @count($result[0]) > 0 ? array_keys($result[0]) : array(); //หาชื่อ field ในตาราง
        $dataProvider3 = new ArrayDataProvider([
            'allModels' => $result,
            'sort' => [
                'attributes' => $attributes,
            ],
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        return $this->render('incidence', [
                    'dataProvider' => $dataProvider,
                    'dataProvider2' => $dataProvider2,
                    'dataProvider3' => $dataProvider3,
        ]);
    }

}
