<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\components\Ccomponent;
use app\modules\office\models\SummaryOperation;
use \app\modules\office\components\Ccomponent as cc;
use app\modules\office\models\LicenseList;

class LicenseController extends Controller {

    public function actionIndex() {
        @$params = \Yii::$app->request->queryParams;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);

        try {
            $query = LicenseList::find();
            if (\Yii::$app->user->can('SuperAdmin')) {
                $query->andFilterWhere(['OR',
                    ['like', 'traffic_number', @$params['search']],
                    ['like', 'traffic_owner', @$params['search']],
                    ['like', 'cid_hash', @$params['search']],
                ]);
                //$query->Where(['cid_hash' => $emp->employee_cid]);
            } else {
                $query->Where(['cid_hash' => $emp->employee_cid]);
            }
            $dataProvider = new ActiveDataProvider([
                'query' => $query,
                'pagination' => [
                    'pageSize' => 10
                ],
            ]);
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $dataProvider = new ActiveDataProvider();
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new LicenseList();
        $model->traffic_status = 1;

        // ตรวจสอบจำนวนทะเบียนที่ผู้ใช้เพิ่ม
        $currentCount = LicenseList::find()->where(['cid_hash' => $emp->employee_cid])->count();
        if ($currentCount >= 2 AND!\Yii::$app->user->can('SuperAdmin')) {
            Yii::$app->session->setFlash('error', 'คุณไม่สามารถเพิ่มทะเบียนรถได้เกิน 2 คัน');
            return $this->redirect(['index']); // กลับไปยังหน้า index
        }
		//echo $emp->employee_cid;
        if ($this->request->isPost) {
            $model->cid_hash = $emp->employee_cid;
            //$model->create_at = new \yii\db\Expression(' NOW() ');
            $model->traffic_owner = $emp->employee_fullname;
            $model->traffic_type = '';
			//Yii::$app->session->setFlash('error', 'บันทึกรายการไม่สำเร็จ');
            if ($model->load($this->request->post()) && $model->save()) {
				Yii::$app->session->setFlash('success', 'บันทึกรายการสำเร็จ');
            }
        } else {
			
            //$model->loadDefaultValues();
        }
		
        return $this->renderAjax('_form', ['model' => $model]);
        //return $this->render('_form', ['model' => @$model]);
    }

    public function actionUpdate($id) {
        $model = LicenseList::findOne($id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

}
