<?php

namespace app\modules\nurse\controllers;

use yii\web\Controller;
use app\modules\hr\models\Employee;
use yii\data\ArrayDataProvider;
use app\modules\inventory\models\Stock;
use app\modules\inventory\models\StockCard;
use yii\data\ActiveDataProvider;

/**
 * Default controller for the `inventory` module
 */
class DefaultController extends Controller {

    public function actionIndex() {
        $param = \Yii::$app->request->get();

        return $this->render('index', [
                    'dataProvider' => @$dataProvider,
                    'data' => @$result
        ]);
    }

}
