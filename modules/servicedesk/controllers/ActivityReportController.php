<?php

namespace app\modules\servicedesk\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\components\Ccomponent;
use app\modules\servicedesk\models\StaffWorkList8h;

class ActivityReportController extends Controller {

//ระบบการบันทึกกิจกรรมการทำงาน Activity Report
    public function actionIndex() {
        $params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);

        $model = StaffWorkList8h::find();
        $model->filterWhere(['OR',
            ['like', 'service_status_id', @$params['status']],
            ['like', 'employee_id', @$params['emp']],
            ['like', 'staff_worklist_issue', @$params['search']
        ]]);
        $Query = "SELECT
employee_fullname,
COUNT(*) AS cc,
COUNT(IF(DATE(staff_worklist_date) = CURRENT_DATE(),1,NULL)) AS cc_today,
COUNT(IF(a.service_status_id = 5,1,NULL)) AS cc_ff,
COUNT(IF(a.service_status_id NOT IN (5,4),1,NULL)) AS cc_wait,
employee_cid,
employee_position_name
FROM staff_worklist a
INNER JOIN hms_product_db.employee b ON a.employee_id= b.employee_id
LEFT JOIN hms_product_db.employee_position c ON b.employee_position_id= c.employee_position_id
LEFT JOIN service_status b ON a.service_status_id = b.service_status_id
-- WHERE date(service_list_date) = current_date()
GROUP BY a.employee_id ORDER BY cc DESC";
        $rows = \Yii::$app->db_servicedesk->createCommand($Query)->queryAll();
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'staff_worklist8h_date' => SORT_DESC,
                    'staff_worklist8h_create_at' => SORT_DESC,
                ]
            ],
        ]);

        return $this->render('index', ['dataProvider' => @$dataProvider, 'data' => $rows]);
    }

    public function actionCreate() {
        $model = new StaffWorkList8h();
        //$model->department_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        //$model->employee_id_staff = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

    public function actionUpdate($id) {
        $model = StaffWorkList8h::findOne($id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

    public function actionAutoSearch($term) {
        if (Yii::$app->request->isAjax) {
            //sleep(2); // for test
            $results = [['id' => $term, 'label' => $term]];
            $q = addslashes($term);
            $data = ServiceList::find()->where(['LIKE', 'staff_worklist_issue', $q])->groupBy('staff_worklist_issue')->all();
            $loop = 0;
            foreach ($data as $model) {
                $loop++;
                if ($loop < 10) {
                    $results[] = [
                        'id' => $model['staff_worklist_issue'],
                        'label' => $model['staff_worklist_issue'],
                    ];
                }
            }

            echo \yii\helpers\Json::encode($results);
        }
    }

}
