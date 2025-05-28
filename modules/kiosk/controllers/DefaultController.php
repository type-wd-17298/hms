<?php

namespace app\modules\kiosk\controllers;

//use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
//use yii\helpers\Json;
//use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

class DefaultController extends Controller {

    public function actionIndex() {
        return $this->render('index', [
        ]);
    }

}
