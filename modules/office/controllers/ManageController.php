<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\modules\office\models\Paperless;
use app\modules\office\models\PaperlessOperation;
use yii\data\ActiveDataProvider;

class ManageController extends Controller {

    public function actionIndex() {
        @$params = \Yii::$app->request->queryParams;
        $query = Paperless::find();
        /*
          if (\Yii::$app->user->can('AdminDep') || \Yii::$app->user->can('User')) { //
          $where = ['department_code' => \Yii::$app->user->identity->profile->hospcode];
          }
          if (\Yii::$app->user->can('SuperAdmin')) { // Admin
          $where = [];
          }
          $query->where($where);
         */

        if (isset($params['search_date']) && !empty($params['search_date'])) {
            list($start2, $end2) = explode(' - ', $params['search_date']);

            if (@$start2) {
                $query->andWhere(['>=', "paperless_date", $start2]);
            }
            if (@$end2) {
                $query->andWhere(['<=', "paperless_date", $end2]);
            }
        }

        $query->filterWhere(['=', 'employee_dep_id', @$params['dep']])
                ->andFilterWhere(['OR',
                    ['like', 'paperless_topic', @$params['search']],
                    ['like', 'budgetyear', @$params['search']],
                    ['like', 'paperless_number', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'paperless_date' => SORT_DESC,
                ]
            ],
        ]);
        $data = [];
        $data['pStatus'] = PaperlessOperation::find()->asArray()->all();
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'var' => $data
        ]);
    }

}
