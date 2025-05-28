<?php

namespace app\modules\line\controllers;

use Yii;
use app\modules\line\models\StaffRegister;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\modules\line\components\CLine;
use app\modules\epayslip\components\Cdata;

/**
 * RegisterController implements the CRUD actions for StaffRegister model.
 */
class RegisterController extends Controller {

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

    public function actionEpayslip() {
        $arrayMessage = [];
        $models = StaffRegister::find()->all();
        foreach ($models as $model) {
            if (isset($model->staff->profile->cid) && strlen($model->staff->profile->cid) == 13 && $model->staff->profile->cid == '1630100011244') {
                $message = Cdata::getStatement();
                $arrayMessage[] = ['userId' => $model->user_id, 'message' => 'สวัสดีค่ะ ' . $model->staff->profile->fullname];
            }
        }
        $app = new CLine();
        $app->pushMessage($arrayMessage);
        //print_r($arrayMessage);
    }

    public function actionEmployee() {
        $arrayMessage = [];
        $models = StaffRegister::find()->all();
        foreach ($models as $model) {
            if (isset($model->staff->profile->cid) && strlen($model->staff->profile->cid) == 13 && $model->staff->profile->cid == '1630100011244') {
                //$message = Cdata::getStatement();
                $arrayMessage[] = ['userId' => $model->user_id, 'message' => "สวัสดีค่ะ \nรายงานสรุปข้อมูลการเข้าปฏิบัติงานของเจ้าหน้าที่ " . $model->staff->profile->fullname];
            }
        }
        $app = new CLine();
        $app->pushMessage($arrayMessage);
        //print_r($arrayMessage);
    }

    public function actionIndex() {


        $dataProvider = new ActiveDataProvider([
            'query' => StaffRegister::find(),
        ]);

        return $this->render('index', [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single StaffRegister model.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new StaffRegister model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new StaffRegister();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing StaffRegister model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->user_id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing StaffRegister model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the StaffRegister model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return StaffRegister the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = StaffRegister::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
