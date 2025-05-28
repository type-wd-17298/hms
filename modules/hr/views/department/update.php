<?php

use yii\helpers\Html;
?>
<div class="project-type-update">
    <h1>แก้ไขรายการ</h1>
    <?=
    $this->render('_form', [
        'model' => $model,
    ])
    ?>
</div>
