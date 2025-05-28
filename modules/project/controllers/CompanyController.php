<?php

namespace app\modules\project\controllers;

use app\modules\project\models\ProjectCompany;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TypeController implements the CRUD actions for ProjectType model.
 */
class CompanyController extends Controller {

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
            $model = ProjectCompany::findOne(['project_company_id' => $id]);
        } else {
            $model = new ProjectCompany();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => ProjectCompany::find(),
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'project_company_id' => SORT_DESC,
                ]
            ],
        ]);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {

            if (\Yii::$app->request->isAjax) {
                //\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return $this->renderAjax('index', [
                            'dataProvider' => $dataProvider,
                            'model' => $model,
                ]);
            }
            //return $this->redirect(['index']);
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
        //$this->findModel($id)->delete();
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
        if (($model = ProjectCompany::findOne(['project_company_id' => $project_company_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSearch($q = null, $id = null) {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['results' => ['id' => '', 'text' => '']];
        //if (!is_null($q)) {
        $connection = \Yii::$app->db_project;
        $query = new \yii\db\Query;

        $query->select(['project_company_id as id', "CONCAT(project_company_name) AS text"])
                ->from('app_project_company');

        $select = "SELECT project_company_id as id,CONCAT(project_company_name) AS text FROM app_project_company
            WHERE project_company_name LIKE '%{$q}%' LIMIT 20";

        // if (!is_null($q))
        //$query->where(['like', 'project_company_name', $q])->limit(200);
        $command = $connection->createCommand($select);
        $data = $command->queryAll();
        $out['results'] = array_values($data);

        if ($id > 0) {
            $out['results'] = ['id' => $id, 'text' => ProjectCompany::find($id)->project_company_name];
        }
        return $out;
    }

}
