<?php

namespace app\modules\project\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\modules\project\models\ProjectPo;
use app\modules\project\models\ProjectType;

class PoController extends Controller {

    public function actionIndex($uid = '') {
        $pid = Yii::$app->request->get('pid');
        $model = (!empty($uid) ? ProjectPo::findOne($uid) : new ProjectPo());
        if (empty($model->project_po_date))
            $model->project_po_date = date('Y-m-d');
        if ($model->isNewRecord)
            $model->project_id = $pid;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success'];
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->renderAjax('_form', [
                    'model' => $model,
                    'pid' => $pid,
                    'uid' => $uid,
        ]);
    }

}
