<?php

namespace app\modules\project\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use app\modules\project\models\Project;
use app\modules\project\models\ProjectType;
use app\modules\project\models\ProjectContract;
use app\modules\project\models\ProjectPo;
use app\modules\project\models\ProjectCompany;
use mdm\autonumber\AutoNumber;
use app\modules\project\models\ProjectBook;
use app\components\Ccomponent;

class DefaultController extends Controller {

    public function actionDashboard() {
        return $this->render('dashboard', [
                    'dataProvider' => @$dataProvider,
        ]);
    }

    public function actionIndex() {
        @$params = \Yii::$app->request->queryParams;
        $query = Project::find();
        $where = [];
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        if (\Yii::$app->user->can('User')) { //
            //$where = ['department_id' => $emp->employee_dep_id];
            $where = ['project_staff' => $emp->employee_cid];
        }
        if (\Yii::$app->user->can('SuperAdmin')) { // Admin
            $where = [];
        }

        //$query->where($where);
        $query->where($where)
                ->filterWhere(['=', 'project_type_id', @$params['reptype']])
                ->andFilterWhere(['OR',
                    ['like', 'project_book_number00', @$params['search']],
                    ['like', 'project_book_number01', @$params['search']],
                    ['like', 'project_book_number02', @$params['search']],
                    ['like', 'project_book_number03', @$params['search']],
                    ['like', 'project_code', @$params['search']],
                    ['like', 'project_name', @$params['search']],
                    ['like', 'project_comment', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'project_date' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionManage() {
        $id = Yii::$app->request->get('id');
        $model = Project::find()->where(['project_id' => $id])->one();
        $query = ProjectContract::find()->where(['project_id' => $model->project_id]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false,
        ]);
        $query2 = ProjectPo::find()->where(['project_id' => $model->project_id]);
        $dataProvider2 = new ActiveDataProvider([
            'query' => $query2,
            'pagination' => false,
        ]);
        return $this->renderAjax('manage', [
                    'dataProvider' => $dataProvider,
                    'dataProviderPO' => $dataProvider2,
                    'model' => $model,
        ]);
    }

    public function actionCreate() {
        $model = new Project();
        $model->project_date = date('Y-m-d');
        $model->project_buy_type = 1;
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->redirect(['index']);
            }
        } else {
            $model->loadDefaultValues();
        }
        return $this->render('_form', [
                    'model' => $model,
        ]);
    }

    public function actionUpdate($id) {
        $model = Project::findOne($id);
        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            return $this->redirect(['index']);
        }
        return $this->render('_form', [
                    'model' => $model,
        ]);
    }

    public function actionGennumber() {
        $post = \Yii::$app->request->post();
        $pid = $post['pid'];
        $number = $post['number'];
        $title = $post['title'];
        $model = Project::findOne($pid);
        $year = (date('Y') + 543);
        if (in_array($number, [0, 1, 2, 3])) {
            $var = "project_book_number0{$number}";
            $model->$var = AutoNumber::generate("P-?????/$year"); //กำหนดเลข
            if ($model->save()) {
                //เก็บ Log การดำเนินการออกเลข
                $log = new ProjectBook();
                $log->project_id = $model->project_id;
                $log->project_book_title = $title;
                $log->project_book_ordernumber = $model->$var;
                $log->staff = $model->whoRecord->fullname;
                $log->save();
            }
        }
    }

    //-----------------------------------------------------------------------------------------------------------------------------------------
    public function actionBook() {
        $query = Project::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'project_id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('book', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionContract() {
        $query = ProjectContract::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'project_contract_id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('contract', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionPo() {

        @$params = \Yii::$app->request->queryParams;
        $query = ProjectPo::find()
                ->joinWith('project');
        $where = [];

        //$query->where($where);
        $query->where($where)
                ->filterWhere(['=', 'project_type_id', @$params['reptype']])
                ->andFilterWhere(['OR',
                    //['like', 'project_book_number00', @$params['search']],
                    //['like', 'project_book_number01', @$params['search']],
                    //['like', 'project_book_number02', @$params['search']],
                    //['like', 'project_book_number03', @$params['search']],
                    ['like', 'project_code', @$params['search']],
                    ['like', 'project_name', @$params['search']],
                    ['like', 'project_comment', @$params['search']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100,
            ],
            'sort' => [
                'defaultOrder' => [
                    'project_po_id' => SORT_DESC,
                ]
            ],
        ]);
        return $this->render('po', [
                    'dataProvider' => $dataProvider,
        ]);
    }

}
