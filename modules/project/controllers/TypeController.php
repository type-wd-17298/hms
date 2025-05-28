<?php

namespace app\modules\project\controllers;

use app\modules\project\models\ProjectType;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TypeController implements the CRUD actions for ProjectType model.
 */
class TypeController extends Controller {

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

    /**
     * Lists all ProjectType models.
     *
     * @return string
     */
    public function actionIndex() {
        $id = \Yii::$app->request->get('id');
        if ($id <> '') {
            $model = ProjectType::findOne(['project_type_id' => $id]);
        } else {
            $model = new ProjectType();
        }
        $dataProvider = new ActiveDataProvider([
            'query' => ProjectType::find(),
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'project_type_id' => SORT_DESC,
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
     * Displays a single ProjectType model.
     * @param int $project_type_id Project Type ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($project_type_id) {
        return $this->render('view', [
                    'model' => $this->findModel($project_type_id),
        ]);
    }

    /**
     * Creates a new ProjectType model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate() {
        $model = new ProjectType();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                return $this->redirect(['index', 'project_type_id' => $model->project_type_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        //Ajax Validation Start
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('create', [
                        'model' => $model,
            ]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing ProjectType model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $project_type_id Project Type ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($project_type_id) {
        $model = $this->findModel($project_type_id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            \Yii::$app->getSession()->setFlash('alert', [
                'body' => 'บันทึกข้อมูลสำเร็จ..',
                'options' => ['class' => 'alert-success']
            ]);
            return $this->redirect(['index', 'project_type_id' => $model->project_type_id]);
        }
//Ajax Validation Start
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('update', [
                        'model' => $model,
            ]);
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ProjectType model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $project_type_id Project Type ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
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
     * @param int $project_type_id Project Type ID
     * @return ProjectType the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($project_type_id) {
        if (($model = ProjectType::findOne(['project_type_id' => $project_type_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
