<?PHP

use app\components\Ccomponent;
use yii\helpers\Html;

$fontSize = 16;
$path = '';
if (!empty($model)) {
    $path = $model->getUploadPath();
}
?>

<?PHP
$man = [];
foreach ($modelProcess as $value) {
    //if (!empty($value->process_comment))
    $man [] = ['time' => $value->process_create, 'name' => $value->emp->employee_fullname, 'position' => @$value->emp->position->employee_position_name, 'pic' => $path . "../laysen/{$value->employee_id}.jpg", 'command' => $value->process_comment];
}
?>

<table class="tables" border="0" width="580" style="font-size: 10pt; border-collapse: collapse;">
    <tr>
        <?PHP
        asort($man);
        foreach ($man as $value) {
            ?>
            <td style="width:25%">
                <table class="table" border="0" style="border-collapse: collapse;">
                    <tr>
                        <td style="text-align:center;text-align:center;font-size: 10pt;">
                            <?= $value['command'] ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align:center;" nowrap>
                            <?= Html::img($value['pic'], ['width' => 120, 'height' => 45]) ?>
                            <br>(<?= $value['name'] ?>)
                            <br><?= $value['position'] ?>
                            <div style="text-align:center;font-size: 8pt;"><!--Digitally Signed by <?= $value['name'] ?><br>--><?= Ccomponent::getThaiDate($value['time'], 'S', 1) ?>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
            <?PHP
        }
        ?>

    </tr>
</table>