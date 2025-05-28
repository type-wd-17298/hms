<?php

namespace app\modules\report\controllers;

use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;
use yii\helpers\Json;
use yii\web\UploadedFile;
use app\modules\mophic\components\Cmophic;

class DefaultController extends Controller {

    public function actionIndex() {
        return $this->render('index');
    }

}
