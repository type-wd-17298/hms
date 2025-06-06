<?php

namespace app\controllers;

use dektrium\user\controllers\SecurityController as BaseSecurityController;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

class ExtsecurityController extends BaseSecurityController {

    public $layout = '@app/themes/custom/layouts/main_login';

    /** @inheritdoc */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['login', 'auth', 'blocked'], 'roles' => ['?']],
                    ['allow' => true, 'actions' => ['login', 'auth', 'logout'], 'roles' => ['@']],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

}
