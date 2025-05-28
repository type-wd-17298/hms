<?php

namespace app\modules\hr\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
//use yii\helpers\Json;
//use yii\web\UploadedFile;
//use app\modules\mophic\components\Cmophic;
//use app\modules\hr\models\Employee;
//use app\modules\hr\models\EmployeeType;
use app\modules\hr\models\EmployeeDep;
use yii\helpers\Html;

class StructureController extends Controller {

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

    public function treeHtml($arr, $parent = 0) {
        $html = '';
        $text = '';
        $data = [];
        foreach ($arr as $value) {
            if ($value['parent'] == $parent) {
                $photo = Html::img('https://st.depositphotos.com/1796420/4113/v/600/depositphotos_41138541-stock-illustration-vector-illustration-of-orange-shield.jpg', ['class' => 'img-fluid', 'width' => '48']);
                $header = Html::tag('div', $value['label'], ['class' => 'font-weight-bold']);
                $body = ''; //Html::tag('div', '...', ['class' => '']);
                $text = Html::tag('div', $header, ['class' => 'card-header', 'style' => 'display: block;']) .
                        Html::tag('div', $body, ['class' => 'card-body']) .
                        $photo .
                        Html::tag('div', Html::tag('p', '<i class="fa-solid fa-chevron-right fa-xs"></i> เพิ่มบุคลากร', ['class' => 'small']), ['class' => 'card-footer']);
                $text = Html::tag('div', $text, ['class' => 'card']);
                $text = Html::a($text, '#', ['data-id' => $value['id'], 'class' => 'dd btnPopup' . ( in_array($value['level'], [1, 2, 3, 4]) ? '' : '')]);
                $data[] = $value['id'];
                if (isset($value['sub']) && count($value['sub']) > 0) {
                    $res = $this->treeHtml($value['sub'], $value['id']);
                    $data = array_merge($data, $res['data']);
                    $text .= $res['html'];
                }
                $html .= Html::tag('li', $text);
            }
        }
        return ['data' => $data, 'html' => Html::tag('ul', $html)];
    }

    public function treeHtmlParent($arr, $parent = 0) {
        $html = '';
        $text = '';
        $data = [$parent];
        foreach ($arr as $value) {
            if ($value['id'] == $parent) {
                $header = Html::tag('div', '<i class="fa fa-windows"></i> ' . $value['label'], ['class' => 'font-weight-bold']);
                $body = Html::tag('div', '...', ['class' => '']);
                $text = Html::tag('div', $header, ['class' => 'card-header', 'style' => 'display: block;']) .
                        Html::tag('div', $body, ['class' => 'card-body']);
                $text = Html::a($text, '#', ['data-id' => $value['id'], 'class' => 'btnPopup card ']);
            }
        }
        $arr = self::categories($arr, $parent);
        $res = self::treeHtml($arr, $parent);
        $text .= $res['html'];
        $data = array_merge($data, $res['data']);
        $html = Html::tag('li', $text);
        return ['data' => $data, 'html' => Html::tag('ul', $html)];
    }

    public function actionIndex() {
        @$params = \Yii::$app->request->queryParams;
        $query = EmployeeDep::find()->where(['employee_dep_status' => 1])->asArray()->orderBy(['employee_dep_parent' => SORT_ASC])->all();
        foreach ($query as $value) {
            $group[] = ['id' => $value['employee_dep_id'], 'parent' => $value['employee_dep_parent'], 'label' => $value['employee_dep_label'], 'level' => $value['employee_dep_level']];
        }
        $group = self::categories($group);
        $Query = "SELECT a.*,b.employee_dep_label AS label
                                  FROM employee_dep a
                                  LEFT JOIN employee_dep b ON a.`employee_dep_id` = b.`employee_dep_parent`
                                  WHERE 1";
        //$data = \Yii::$app->db->createCommand($Query)->queryAll();
        $sort = @count($query[0]) > 0 ? array_keys($query[0]) : []; //หาชื่อ field ในตาราง
        $dataProvider = new ArrayDataProvider([
            'allModels' => $query,
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);

        $tree = self::treeHtml($group, 0);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'tree' => $tree['html'],
        ]);
    }

    public function actionBranch() {
        $params = \Yii::$app->request->queryParams;
        if (isset($params['idu'])) {
            $model = EmployeeDep::findOne($params['idu']);
        } else {
            $model = new EmployeeDep();
        }
        if (Yii::$app->request->isAjax && $model->load($this->request->post())) {
            $model->save();
        }
        $query = EmployeeDep::find()->asArray()->all();
        foreach ($query as $value) {
            $group[] = ['id' => $value['employee_dep_id'], 'parent' => $value['employee_dep_parent'], 'label' => $value['employee_dep_label'], 'level' => $value['employee_dep_level']];
        }
        $tree = self::treeHtmlParent($group, $params['id']);
        $data = EmployeeDep::find()->where(['IN', 'employee_dep_id', $tree['data']]);
        $dataArray = $data->asArray()->all();
        $attributes = @count($dataArray[0]) > 0 ? array_keys($dataArray[0]) : array(); //หาชื่อ field ในตาราง
        $dataProvider = new ArrayDataProvider([
            'allModels' => $dataArray,
            'sort' => [
                'attributes' => $attributes,
            ],
            'pagination' => [
                'pageSize' => 500,
            ],
        ]);

        return $this->renderAjax('branch', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'dataArray' => $dataArray,
                    'tree' => $tree,
        ]);
    }

}
