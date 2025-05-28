<?php

namespace app\modules\servicedesk\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\components\Ccomponent;
use app\modules\servicedesk\models\SoftwareList;

class SoftwareController extends Controller {

    public function actionIndex() {
        $params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = SoftwareList::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'software_list_creat_at' => SORT_DESC,
                ]
            ],
        ]);
        $Query = "SELECT
                            COUNT(*) AS cc,
                            COUNT(IF(a.software_status_id = 1,1,NULL)) AS cc_wait,
                            a.software_status_id,
                            software_status_name
                            FROM service_software_list a
                            LEFT JOIN service_software_status b ON a.software_status_id = b.software_status_id
                            -- WHERE date(software_list_date) = current_date()
                            GROUP BY a.software_status_id ";
        $rows = \Yii::$app->db_servicedesk->createCommand($Query)->queryAll();
        $value = [];
        foreach ($rows as $value) {
            $data[$value['software_status_id']] = $value['cc'];
            $data['cc'] += $value['cc'];
            $data['cc_wait'] += $value['cc_wait'];
        }
        return $this->render('index', ['dataProvider' => @$dataProvider, 'data' => $data]);
    }

    public function actionCreate() {
        $model = new SoftwareList();
        $model->department_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model->employee_id_staff = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
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
        $model = SoftwareList::findOne($id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

}
