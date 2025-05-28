<?php

namespace app\modules\plan\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use app\modules\plan\models\Plan;
use yii\data\ActiveDataProvider;
use app\components\Ccomponent;

class DefaultController extends Controller {

    public function actionIndex() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        //$model = new Plan();
        @$params = \Yii::$app->request->queryParams;

        $query = Plan::find();

        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('PlanAdmin')) {

        } else {
            $query->andWhere(['employee_id' => $emp->employee_id]);
        }
        $query->andWhere(['plan_budget_year' => 2568]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10000
            ],
            'sort' => [
                'defaultOrder' => [
                    'create_at' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('index', ['model' => @$model, 'dataProvider' => $dataProvider]);
    }

    public function actionCreate() {
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = new Plan();
        $model->employee_id = $emp->employee_id;
        $model->department_id = $emp->employee_dep_id;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->renderAjax('_form', ['model' => $model]);
        //return $this->render('create', ['model' => @$model, 'dataProvider' => $dataProvider]);
    }

    public function actionUpdate($id) {
        $model = Plan::findOne($id);
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {

            }
        }
        return $this->renderAjax('_form', ['model' => $model]);
    }

    /*
      public function actionActivity() {
      @$params = \Yii::$app->request->queryParams;
      $query = DepActivity::find();

      if (isset($params['activity_date']) && !empty($params['activity_date'])) {
      list($start2, $end2) = explode(' - ', $params['activity_date']);

      if (@$start2) {
      $query->andWhere(['>=', "dep_activity_date", $start2]);
      }
      if (@$end2) {
      $query->andWhere(['<=', "dep_activity_date", $end2]);
      }
      }

      $query->filterWhere(['=', 'department_code', @$params['dep']])
      ->andFilterWhere(['OR',
      ['like', 'dep_activity_title', @$params['search']],
      ['like', 'department_code', @$params['search']],
      ['like', 'dep_activity_summary', @$params['search']],
      ['like', 'dep_activity_purpose', @$params['search']],
      ]);

      $dataProvider = new ActiveDataProvider([
      'query' => $query,
      'pagination' => [
      'pageSize' => 20
      ],
      'sort' => [
      'defaultOrder' => [
      'dep_activity_date' => SORT_DESC,
      ]
      ],
      ]);

      return $this->render('activity', [
      'listDataProvider' => $dataProvider,
      ]);
      }
     */
}
