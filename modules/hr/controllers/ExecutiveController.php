<?php

namespace app\modules\hr\controllers;

use app\modules\hr\models\Executive;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\hr\models\ExecutiveHasCdepartment;

/**
 * TypeController implements the CRUD actions for ProjectType model.
 */
class ExecutiveController extends Controller {

    /**
     * @inheritDoc
     */
    public function behaviors() {
        return array_merge(
                parent::behaviors(),
                [
                    'verbs' => [
                        'class' => VerbFilter::className(),
                        'actions' => [
                            'delete' => ['POST'],
                        ],
                    ],
                ]
        );
    }

    public function actionIndex() {

        $id = \Yii::$app->request->get('id');
        if ($id <> '') {
            $model = Executive::findOne(['employee_executive_id' => $id]);
        } else {
            $model = new Executive();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => Executive::find(),
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'employee_executive_level' => SORT_ASC,
                    'employee_executive_sort' => SORT_ASC,
                ]
            ],
        ]);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if (is_array($model->dep_code)) {
                foreach ($model->dep_code as $value) {
                    $depModel = ExecutiveHasCdepartment::findOne(['employee_executive_id' => $model->employee_executive_id, 'employee_dep_id' => $value]) ?: new ExecutiveHasCdepartment();
                    if ($depModel->isNewRecord) {
                        $depModel->employee_executive_id = $model->employee_executive_id;
                        $depModel->employee_dep_id = $value; //เพิ่มหน่วยงาน
                        $depModel->create_at = new \yii\db\Expression('NOW()');
                    }
                    $depModel->save();
                }
                //ลบข้อมูลเมื่อมีการเลือกบางรายการ
                //ExecutiveHasCdepartment::deleteAll(['AND', ['employee_executive_id1' => $model->employee_executive_id], ['NOT IN', 'department_id', $model->dep_code]]);
            } else {
                //ลบข้อมูลเมื่อไม่มีการเลือกรายการใดๆ
                ExecutiveHasCdepartment::deleteAll(['employee_executive_id' => $model->employee_executive_id]);
            }
        }

        $dep = [];
        $checkDep = ExecutiveHasCdepartment::find()->where(['employee_executive_id' => $model->employee_executive_id])->all();
        foreach ($checkDep as $value) {
            $dep[$value->employee_dep_id] = $value->department->employee_dep_label;
        }
        if (count($dep) > 0) {
            $model->dep_code = $dep;
        }


//Ajax Validation Start
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                        'dataProvider' => $dataProvider,
                        'model' => $model,
            ]);
        }

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
        ]);
    }

    /**
     * Creates a new ProjectType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the ProjectType model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $project_company_id Project Type ID
     * @return ProjectType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($project_company_id) {
        if (($model = Executive::findOne(['employee_executive_id' => employee_executive_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearch($q = null, $id = null) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        //if (!is_null($q)) {
        $query = new \yii\db\Query;
        $query->select(['employee_executive_id as id', "CONCAT(employee_executive_name) AS text"])
                ->from('app_project_company');

        if (!is_null($q))
            $query->where(['like', 'project_company_name', $q])->limit(200);
        $command = $query->createCommand();
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => Executive::find($id)->employee_executive_name];
        }
        return $out;
    }

    public function actionEditvalue() {
        $param = \Yii::$app->request->post();
        $model = Executive::findOne($param['editableKey']); // your model can be loaded here
// Check if there is an Editable ajax request
        if (isset($_POST['hasEditable'])) {
// use Yii's response format to encode output as JSON
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

// read your posted model attributes
            foreach ($param['Executive'] as $values) {
                $column = array_keys($values);
                $column = $column[0];
                $value = $model->$column = $values[$column];
            }
            if ($model->load(\Yii::$app->request->post()) && $model->save()) {
                return ['output' => $value, 'message' => ''];
            } else {
                return ['output' => '', 'message' => ''];
            }
        }
    }

}
