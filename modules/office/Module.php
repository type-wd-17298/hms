<?php

namespace app\modules\office;

/**
 * register module definition class
 */
class Module extends \yii\base\Module {

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\office\controllers';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        //$this->viewPath = '@app/themes/vuexy/views/office2';
    }

}
