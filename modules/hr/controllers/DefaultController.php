<?php

namespace app\modules\hr\controllers;

//use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
//use yii\helpers\Json;
//use yii\web\UploadedFile;
use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeType;
use app\modules\hr\models\Executive;
use app\modules\hr\models\EmployeePositionHead;
use app\modules\hr\models\EmployeeDep;
use app\modules\hr\models\EmployeePosition;
use yii\helpers\ArrayHelper;

class DefaultController extends Controller {

    public function actionIndex() {

        @$params = \Yii::$app->request->queryParams;
        $data = Employee::find()
                ->select(['cc' => 'count(*)', 'id' => 'employee.employee_type_id'])
                ->joinWith('empType')
                ->groupBy(['employee.employee_type_id'])
                ->where(['status' => 1])
                ->andFilterWhere(['department_code' => @$params['dep']])
                ->andFilterWhere(['OR',
                    ['like', 'employee_fullname', @$params['search']],
                    ['like', 'employee_cid', @$params['search']],
                    ['like', 'employee_id', @$params['search']],
                ])
                ->asArray()
                ->all();

        foreach ($data as $index => &$value) {
            @$data['cc'][$value['id']] = $value['cc'];
            @$data['sum'] += $value['cc'];
        }

        $query = Employee::find()
                ->where(['status' => 1])
                ->andFilterWhere(['=', 'department_code', @$params['dep']])
                ->andFilterWhere(['OR',
            ['like', 'employee_fullname', @$params['search']],
            ['like', 'employee_cid', @$params['search']],
            ['like', 'employee_id', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 50
            ],
//            'sort' => [
//                'defaultOrder' => [
//                    'memorandum_date' => SORT_DESC,
//                ]
//            ],
        ]);

        $empType = EmployeeType::find()->asArray()->all();

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'empType' => $empType,
                    'empData' => $data,
        ]);
    }

    public function actionExecutive($id) {
        //ลบข้อมูล
        if (isset($_GET['did']) && !empty($_GET['did'])) {
            EmployeePositionHead::findOne(['employee_head_id' => \Yii::$app->request->get('did')])->delete();
        }

        $emp = Employee::find()->where(['OR', ['employee_id' => $id], ['employee_cid' => $id]])->one();

        if (!$emp) {
            $emp = new Employee();
            if (!empty(\Yii::$app->request->get('var'))) {
                $var = \Yii::$app->request->get('var');
                $emp->employee_cid = $var['cid'];
                $emp->employee_fullname = str_replace('#', ' ', $var['th_fullname']);
                $emp->employee_address = str_replace('#', ' ', $var['address']);
            }
        }

        if ($this->request->isPost && $emp->load($this->request->post())) {
            $emp->save();
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
        }
        $model = new EmployeePositionHead();
        $model->create = new \yii\db\Expression('NOW()');
        $model->employee_id = $emp->employee_id; //เพิ่มเจ้าหน้าที่
        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->save();
        }

        $data = EmployeePositionHead::find()->where(['employee_id' => $emp->employee_id, 'active' => 1])->orderBy(['create' => SORT_DESC])->all();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $data,
            'pagination' => [
                'pageSize' => 200,
            ],
        ]);
        $lists = Executive::find()->where(['employee_executive_status' => 1])->orderBy(['employee_executive_level' => SORT_ASC])->asArray()->all();
        $list = ArrayHelper::map($lists, 'employee_executive_id', 'employee_executive_name');
        //-----------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
        $deps = EmployeeDep::find()->joinWith('type')->where(['employee_dep_status' => 1])->orderBy(['employee_dep_level' => SORT_ASC])->asArray()->all();
        $dep = ArrayHelper::map($deps, 'employee_dep_id', 'employee_dep_label', 'type.category_name');

        $position = EmployeePosition::find()->orderBy(['employee_position_name' => SORT_ASC])->asArray()->all();
        $position = ArrayHelper::map($position, 'employee_position_id', 'employee_position_name');

        $type = EmployeeType::find()->orderBy(['employee_type_name' => SORT_ASC])->asArray()->all();
        $type = ArrayHelper::map($type, 'employee_type_id', 'employee_type_name');

        return $this->renderAjax('executive', [
                    'model' => $model,
                    'list' => $list,
                    'emp' => $emp,
                    'dep' => $dep,
                    'type' => $type,
                    'position' => $position,
                    'dataProvider' => $dataProvider,
                    'id' => $id
        ]);
    }

}
