<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\components\Ccomponent;
use app\modules\office\models\SummaryOperation;
use \app\modules\office\components\Ccomponent as cc;

class DefaultController extends Controller {

    public function actionSync() {

    }

    public function actionIndex() {
        return $this->render('index');
    }

    public function actionOperation() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        cc::OperationUpdate($emp->employee_id);
        $model = SummaryOperation::find()->where(['employee_id' => $emp->employee_id])->one();
        return $model;
    }

    public function actionMyoffice() {
        @$params = \Yii::$app->request->queryParams;
        $where = ' DATE(create_at) >= CURRENT_DATE()-1 ';
        if (isset($params['search_date']) && !empty($params['search_date'])) {
            list($start2, $end2) = explode(' - ', $params['search_date']);
            $where = " DATE(create_at) between  '{$start2}'  and '{$end2}' ";
        }
        try {
            $query = "
                SELECT
DATE(create_at) AS dd,
paperless_official_from AS ff,
paperless_topic AS topic,
paperless_official_booknumber AS bn,
paperless_official_number AS bnn,
employee_dep_label AS dep
FROM paperless_official a
LEFT JOIN employee_dep d ON a.employee_dep_id = d.employee_dep_id
WHERE {$where}
AND paperless_official_type = 'BRN'
ORDER BY create_at ASC
                ";
            $result = \Yii::$app->db->createCommand($query)->queryAll();
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
        } catch (\Exception $e) {
            throw new \yii\web\HttpException(405, 'Error MySQL Query' . $e->getMessage());
            $dataProvider = new ArrayDataProvider();
        }

        return $this->render('myoffice', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
