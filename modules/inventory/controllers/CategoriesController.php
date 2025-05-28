<?php

namespace app\modules\inventory\controllers;

use Yii;
use app\modules\inventory\models\ItemsCategories;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\bootstrap4\ActiveForm;

/**
 * CategoriesController implements the CRUD actions for ItemsCategories model.
 */
class CategoriesController extends Controller {

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all ItemsCategories models.
     * @return mixed
     */
    public function actionIndex($id = null) {
        if ($id > 0) {
            $model = $this->findModel($id);
        } else {
            $model = new ItemsCategories();
        }

        $dataProvider = new ActiveDataProvider([
            'query' => ItemsCategories::find(),
            'pagination' => false,
        ]);

        $elements = ItemsCategories::find()->asArray()->orderBy(['categories_id' => 'asc'])->all();
        $attribute = $model->buildTree($elements);
        //--------------------------------------------------------
        $arr = [];
        foreach ($attribute as $row) {
            $arr[] = $row;
        }
        //--------------------------------------------------------

        $dataProvider = new \yii\data\ArrayDataProvider([
            'allModels' => $arr,
            'pagination' => false,
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
                    'model' => $model,
                    'attribute' => $attribute,
        ]);
    }

    /**
     * Displays a single ItemsCategories model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ItemsCategories model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new ItemsCategories();

        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } elseif ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('alert', [
                        'body' => 'บันทึกข้อมูลสำเร็จ..',
                        'options' => ['class' => 'alert-success']
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('alert', [
                        'body' => 'บันทึกข้อมูลไม่สำเร็จ..',
                        'options' => ['class' => 'alert-warning']
                    ]);
                }
            } catch (\Exception $exc) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ไม่สามารถบันทึกรายการได้..',
                    'options' => ['class' => 'alert-warning']
                ]);
            }

            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Updates an existing ItemsCategories model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {

        $model = $this->findModel($id);

        if (Yii::$app->request->isAjax) {
            $model->load(Yii::$app->request->post());
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        } elseif ($model->load(Yii::$app->request->post())) {
            try {
                if ($model->save()) {
                    Yii::$app->getSession()->setFlash('alert', [
                        'body' => 'บันทึกข้อมูลสำเร็จ..',
                        'options' => ['class' => 'alert-success']
                    ]);
                } else {
                    Yii::$app->getSession()->setFlash('alert', [
                        'body' => 'บันทึกข้อมูลไม่สำเร็จ..',
                        'options' => ['class' => 'alert-warning']
                    ]);
                }
            } catch (\Exception $exc) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ไม่สามารถบันทึกรายการได้..',
                    'options' => ['class' => 'alert-warning']
                ]);
            }


            return $this->redirect(['index', 'id' => $model->categories_id]);
        } else {
            return $this->render('update', [
                        'model' => $model
            ]);
        }
    }

    /**
     * Deletes an existing ItemsCategories model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $check = \app\modules\inventories\models\Items::find()->where(['categories_id' => $id])->count();
        if ($check == 0) {
            if ($this->findModel($id)->delete()) {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ลบข้อมูลสำเร็จ...',
                    'options' => ['class' => 'alert-success']
                ]);
            } else {
                Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'ลบข้อมูลไม่สำเร็จ..',
                    'options' => ['class' => 'alert-danger']
                ]);
            }
        } else {
            Yii::$app->getSession()->setFlash('alert', [
                'body' => 'ไม่สามารถลบข้อมูลนี้ได้ เนื่องจากมีการใช้งานอยู่ค่ะ..',
                'options' => ['class' => 'alert-danger']
            ]);
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the ItemsCategories model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ItemsCategories the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = ItemsCategories::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
