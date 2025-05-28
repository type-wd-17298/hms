<?php

namespace app\modules\office\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ActiveDataProvider;
//use yii\data\ArrayDataProvider;
//use yii\helpers\Json;
//use yii\web\UploadedFile;
use app\modules\office\models\BookMeetingRoom;
use app\modules\office\models\BookMeetingRoomList;
use app\components\Ccomponent;
use yii\filters\VerbFilter;
use yii\bootstrap4\ActiveForm;

class BookController extends Controller {

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
        $model = BookMeetingRoom::find()->where(['bk_meetingroom_status' => 1])->orderBy(['bk_meetingroom_sort' => SORT_DESC])->all();
        return $this->render('index', [
                    'data' => $model
        ]);
    }

    public function actionCalendar() {
        return $this->renderAjax('_calendar');
    }

    public function actionEventDetail($date) {
        $model = BookMeetingRoomList::find()->where(['OR', ['date_event' => $date], ['BETWEEN', 'DATE(date_event_timein)', $date, $date]]);
        $dataProvider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 20
            ],
            'sort' => [
                'defaultOrder' => [
                    'date_event' => SORT_DESC,
                ]
            ],
        ]);
        $data = [];
        return $this->renderAjax('_gridviewDetail', [
                    'date' => $date,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionEventCalendar() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        //$model = BookMeetingRoomList::find()->orderBy(['create_at' => SORT_DESC, 'date_event' => SORT_DESC])->limit(100)->all();
		$model = BookMeetingRoomList::find()->orderBy(['create_at' => SORT_DESC, 'date_event' => SORT_DESC])->limit(1000)->all();
        $events = [];
        foreach ($model as $row) {
            $Event['title'] = $row->subject;
            if (!empty($row->timein)) {
                $Event['start'] = date('Y-m-d H:i:s', strtotime($row->date_event . " " . $row->timein));
                $Event['end'] = date('Y-m-d H:i:s', strtotime($row->date_event . " " . $row->timeout));
            } else {
                $Event['start'] = $row->date_event_timein;
                $Event['end'] = $row->date_event_timeout;
            }

            $Event['id'] = $row->id;
            //$Event['allDay'] = true;
            $Event['color'] = @$row->rooms->bk_meetingroom_color;
            $events[] = $Event;
        }
        return $events;
    }

    public function actionCreate() {
        $model = new BookMeetingRoomList();
        if (isset($_GET['id']))
            $model->bk_meetingroom_id = $_GET['id'];
        $model->employee_dep_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        $model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $model->create_at = new \yii\db\Expression('NOW()');

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
            $cc = ActiveForm::validate($model);
            if (count($cc) > 0)
                return $cc;
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success'];
                //return $this->redirect(['index']);
            }
        } else {

            //$model->loadDefaultValues();
        }

        return $this->renderAjax('_form', [
                    'model' => $model,
                        //'initialPreview' => $initialPreview,
                        //'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionUpdate($id) {
        $model = BookMeetingRoomList::findOne($id);
        //$model->employee_dep_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_dep_id;
        //$model->employee_id = Ccomponent::Emp(Yii::$app->user->identity->profile->cid)->employee_id;
        $model->update_at = new \yii\db\Expression('NOW()');
        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                \Yii::$app->getSession()->setFlash('alert', [
                    'body' => 'บันทึกข้อมูลสำเร็จ..',
                    'options' => ['class' => 'alert-success']
                ]);
                \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return ['status' => 'success'];
                //return $this->redirect(['index']);
            }
        }

        return $this->renderAjax('_form', [
                    'model' => $model,
                        //'initialPreview' => $initialPreview,
                        //'initialPreviewConfig' => $initialPreviewConfig
        ]);
    }

    public function actionDelete($id) {
        BookMeetingRoomList::findOne($id)->delete();
        if (\Yii::$app->request->isAjax) {
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return ['success' => true];
        }
        return $this->redirect(['index']);
    }

}
