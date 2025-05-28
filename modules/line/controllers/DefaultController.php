<?php

namespace app\modules\line\controllers;

use yii\web\Controller;
use app\modules\hr\models\Employee;
use app\modules\line\components\CLine;
use app\modules\line\components\CLine2;
use app\modules\line\components\lineBot;

/**
 * Default controller for the `line` module
 */
class DefaultController extends Controller {

    public function beforeAction($action) {
        if ($action->id == 'callback') {
            $this->enableCsrfValidation = false; //ปิดการใช้งาน csrf
        }
        if ($action->id == 'callback2') {
            $this->enableCsrfValidation = false; //ปิดการใช้งาน csrf
        }
        return parent::beforeAction($action);
    }

    public function actionCallback() {
        $app = new CLine();
        $app->start('/line/default/callback');
    }

    public function actionCallback2() {
        $app = new CLine2();
        $app->start('/line/default/callback2');
    }

    public function actionIndex() {
        return $this->render('index');
    }

    /* ลงทะเบียนเชื่อมต่อ LINE */

    public function actionLinecallback() {
        $cid = \yii::$app->user->identity->profile->cid;
        $model = Employee::findOne(['employee_cid' => $cid]);
        $line = \app\modules\line\components\lineBot::line();
        $model->employee_linetoken = $line->access_token;
        $model->save();
        $linebot = new lineBot();
        $message = " การลงทะเบียนกับระบบ LINE HMS-Somdej17 สำเร็จ";
        $linebot->send($message, [$line->access_token]); //ส่ง line ให้ส่วนตัว

        \Yii::$app->getSession()->setFlash('alert', [
            'body' => 'ลงทะเบียนกับระบบ LINE HMS-Somdej17 สำเร็จ..',
            'options' => ['class' => 'alert-success']
        ]);
        return $this->redirect(['//']);
    }

}
