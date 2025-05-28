<?php

namespace app\modules\project\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use app\modules\project\models\Project;

class ReportController extends Controller {

    public function actionSummary() {
        //@$params = \Yii::$app->request->queryParams;
        $param = \Yii::$app->request->get();
        $where = '';
        if (isset($param['date_between_a']) && $param['date_between_a'] <> '' && isset($param['date_between_b'])) {
            $startDate = $param['date_between_a'];
            $endDate = $param['date_between_b'];
            $where .= " AND project_po_date between '{$startDate}' and '{$endDate}'  ";
        }

        $Query = "SELECT
a.project_po_date as pdate
,project_name
,project_po_cost
,TRIM(project_company_name) AS project_company_name
,project_po_book
,project_type_prefer_name
,employee_fullname
FROM app_project_po a
LEFT JOIN app_project b ON a.project_id = b.project_id
LEFT JOIN app_project_company c ON b.project_company_id = c.project_company_id
LEFT JOIN app_project_type_prefer p ON b.project_type_prefer_id = p.project_type_prefer_id
LEFT JOIN hms_product_db.employee e ON md5(e.employee_cid) = md5(b.project_staff)
WHERE 1 {$where}
    ORDER BY project_po_date ASC
";

        $data = \Yii::$app->db_project->createCommand($Query)->queryAll();
        $sort = @count($data[0]) > 0 ? array_keys($data[0]) : []; //หาชื่อ field ในตาราง
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 2000,
            ],
            'sort' => [
                'attributes' => $sort,
            ]
        ]);
        return $this->render('summary', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
