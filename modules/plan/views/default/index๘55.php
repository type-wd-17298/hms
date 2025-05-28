<?PHP

use yii\bootstrap4\Html;
use yii\helpers\Url;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use app\components\Ccomponent;

?>
<?PHP

echo $this->render('_gridview', [
    'model' => $model,
    'dataProvider' => $dataProvider
]);
?>