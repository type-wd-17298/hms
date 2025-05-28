<?php

namespace app\modules\hr\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\modules\hr\models\EmployeeCategory;
//use app\modules\hr\models\Employee;
//use app\modules\hr\models\EmployeeType;
use app\modules\hr\models\EmployeeDep;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeType;
use yii\helpers\Html;

class DepartmentController extends Controller {

    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'addexecutive' => ['post'],
                ],
            ],
        ];
    }

    public function categories($arr, $parent = 0) {
        $array = [];
        foreach ($arr as $value) {
            if ($value['parent'] == $parent) {
                $sub = $this->categories($arr, $value['id']);
                $array[] = array_merge($value, ['sub' => $sub]);
            }
        }
        return $array;
    }

    public function actionIndex() {
        @$params = \Yii::$app->request->queryParams;
        $query = EmployeeDep::find()
                //->where(['employee_dep_status' => 1])
                ->andFilterWhere(['=', 'employee_dep_code', @$params['dep']])
                ->andFilterWhere(['OR',
            ['like', 'employee_dep_label', @$params['search']],
            ['like', 'employee_dep_code', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 200
            ],
            'sort' => [
                'defaultOrder' => [
                    'employee_dep_status' => SORT_DESC,
                    'employee_dep_parent' => SORT_ASC,
                    'category_id' => SORT_DESC,
                    'employee_dep_label' => SORT_ASC,
                ]
            ],
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'empType' => @$empType,
                    'empData' => @$data,
        ]);
    }

    public function actionManage($id = '') {
        $model = EmployeeDep::findOne($id) ?: new EmployeeDep();
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

        }
        $lists = EmployeeCategory::find()->where(['status' => 1])->orderBy(['category_name' => SORT_ASC])->asArray()->all();
        $lists = ArrayHelper::map($lists, 'category_id', 'category_name');
        $dep = EmployeeDep::find()->joinWith('type')->where(['employee_dep_status' => 1])->orderBy(['employee_dep_level' => SORT_ASC])->asArray()->all();
        $dep = ArrayHelper::map($dep, 'employee_dep_id', 'employee_dep_label', 'type.category_name');
        return $this->renderAjax('_form', [
                    'model' => $model,
                    'lists' => $lists,
                    'dep' => $dep,
        ]);
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }
        return $this->redirect(['index']);
    }

}
