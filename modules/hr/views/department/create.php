<?php

use yii\helpers\Html;

$this->title = 'เพิ่มรายการ';
?>
<div class="project-type-create">
    <h1><?= Html::encode($this->title) ?></h1>

    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>

</div>
