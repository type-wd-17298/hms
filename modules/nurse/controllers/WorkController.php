<?php

namespace app\modules\nurse\controllers;

use yii\web\Controller;
use app\modules\hr\models\Employee;
use yii\data\ArrayDataProvider;
use app\modules\inventory\models\Stock;
use app\modules\inventory\models\StockCard;
use yii\data\ActiveDataProvider;
use app\modules\nurse\models\RSDiaryGG;

class WorkController extends Controller {

    public function actionIndex() {
        $param = \Yii::$app->request->get();

        return $this->render('index', [
                    'dataProvider' => @$dataProvider,
                    'data' => @$result
        ]);
    }

    public function actionReport() {
        $param = \Yii::$app->request->get();

        $query = RSDiaryGG::find();
        /*
          $db = \Yii::$app->db;
          try {
          $query = "SELECT * FROM report_shift_diary_gg a
          LEFT JOIN report_type_shift b ON a.report_type_shift_id = b.report_type_shift_id
          LEFT JOIN employee_dep c ON a.employee_dep_id = c.employee_dep_id
          ";
          $data = $db->createCommand($query)->queryAll();
          } catch (\Exception $ex) {
          $data = [];
          throw new \yii\web\HttpException(405, 'Error MySQL Query' . $ex->getMessage());
          }
         */

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20
            ],
//            'sort' => [
//                'defaultOrder' => [
//                    'approval_id' => SORT_DESC,
//                ]
//            ],
        ]);
        return $this->render('report', [
                    'dataProvider' => @$dataProvider,
        ]);
    }

}
