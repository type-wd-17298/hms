<?php

namespace app\modules\kiosk;

/**
 * register module definition class
 */
class Module extends \yii\base\Module {

    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'app\modules\kiosk\controllers';

    /**
     * {@inheritdoc}
     */
    public function init() {
        parent::init();
        $this->layout = '@app/themes/custom/layouts/main-kiosk';
    }

}
