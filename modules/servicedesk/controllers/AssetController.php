<?php

namespace app\modules\servicedesk\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\components\Ccomponent;
use app\modules\servicedesk\models\AssetList;

class AssetController extends Controller {

    public function actionIndex() {
        $params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = AssetList::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20,
            ],
                /*
                  'sort' => [
                  'defaultOrder' => [
                  'service_list_creat_at' => SORT_DESC,
                  ]
                  ],
                 */
        ]);
        $Query = "SELECT
                            COUNT(IF(DATE(service_list_date) = CURRENT_DATE(),1,NULL)) AS cc,
                            COUNT(IF(a.service_status_id = 1,1,NULL)) AS cc_wait,
                            a.service_status_id,
                            service_status_name
                            FROM service_list a
                            LEFT JOIN service_status b ON a.service_status_id = b.service_status_id
                            -- WHERE date(service_list_date) = current_date()
                            GROUP BY a.service_status_id ";
        $rows = \Yii::$app->db_servicedesk->createCommand($Query)->queryAll();

        $value = [];
        foreach ($rows as $value) {
            $data[$value['service_status_id']] = $value['cc'];
            $data['cc'] += $value['cc'];
            $data['cc_wait'] += $value['cc_wait'];
        }
        return $this->render('index', ['dataProvider' => @$dataProvider, 'data' => $data]);
    }

    public function actionTicket() {
        return $this->render('ticket');
    }

    public function actionCreate() {
        $model = new AssetList();
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
        $model = AssetList::findOne($id);
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
            $data = AssetList::find()->where(['LIKE', 'service_list_issue', $q])->groupBy('service_list_issue')->all();
            $loop = 0;
            foreach ($data as $model) {
                $loop++;
                if ($loop < 10) {
                    $results[] = [
                        'id' => $model['service_list_issue'],
                        'label' => $model['service_list_issue'],
                    ];
                }
            }

            echo \yii\helpers\Json::encode($results);
        }
    }

}
