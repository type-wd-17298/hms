<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
//use yii\data\ArrayDataProvider;
//use yii\helpers\Json;
//use yii\web\UploadedFile;
use app\modules\office\models\ApprovalDead;
use app\components\Ccomponent;
use yii\filters\VerbFilter;
use yii\bootstrap4\ActiveForm;
use mdm\autonumber\AutoNumber;

class DeadController extends Controller {

    public function behaviors() {
        return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ]
        );
    }

    public function actionIndex() {


        $params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('DeathAdmin')) {
            $model = ApprovalDead::find();
        } else {
            $model = ApprovalDead::find()->where(['employee_id' => $emp->employee_id]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20,
            ],
            'sort' => [
                'defaultOrder' => [
                    'dead_create' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('index', [
                    'dataProvider' => @$dataProvider,
                    'data' => $model,
        ]);
    }

    public function actionCreate() {
        $model = new ApprovalDead();

        $model->department_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;

        if ($this->request->isAjax) {
            \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
            if ($model->load($this->request->post()) && $model->validate()) {
                $model->dead_id_number = AutoNumber::generate('DEATH' . (date('Y') + 543) . '-?????');
                $model->staff_name = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_fullname;
                list($cid, $name) = explode(':', $model->dead_infomation);
                $model->dead_cid = $cid;
                $model->save();
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                return ['status' => 'success'];
            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

    public function actionUpdate($id) {
        $model = BookMeetingRoomList::findOne($id);
        //$model->employee_dep_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        //$model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $model->update_at = new \yii\db\Expression('NOW()');
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success'];
                //return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('_form', [
                    'model' => $model,
                        //'initialPreview' => $initialPreview,
                        //'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

//    public function actionDelete($id) {
//        BookMeetingRoomList::findOne($id)->delete();
//        if (\Yii::$app->request->isAjax) {
//            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
//            return ['success' => true];
//        }
//        return $this->redirect(['index']);
//    }
    public function actionPatientlist($q = null, $id = null) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $query = \Yii::$app->db_hosxp;
            $sqlQuery = "SELECT CONCAT(cid,':',pname,'',fname,' ',lname) as id,CONCAT(hn,' ',pname,'',fname,' ',lname) AS text FROM patient WHERE hn LIKE '%{$q}%' OR cid LIKE '%{$q}%' OR fname LIKE '%{$q}%' LIMIT 10;";
            $command = $query->createCommand($sqlQuery);
            $data = $command->queryAll();
            $out['results'] = array_values($data);
        }
        // if ($id > 0) {
        //$out['results'] = ['id' => $id, 'text' => \app\modules\servicedesk\models\AssetList::find($id)->fullname];
        //}
        return $out;
    }

}
