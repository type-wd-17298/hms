<?php
/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

use yii\bootstrap4\Html;
use yii\widgets\Menu;

/** @var dektrium\user\models\User $user */
$user = Yii::$app->user->identity;
$networksVisible = count(Yii::$app->authClientCollection->clients) > 0;

$controller = $this->context;
$route = $controller->route;
?>

<div class="card panel-default">
    <div class="card-heading">
        <h3 class="card-title d-none">
            <?=
            Html::img($user->profile->getAvatarUrl(24), [
                'class' => 'img-rounded',
                'alt' => $user->username,
            ])
            ?>
            <?= $user->username ?>
        </h3>
    </div>
    <div class="card-body">
        <ul class="nav nav-pills flex-column mt-md-0 mt-1">
            <li class="nav-item">
                <?php
                $url = '/user/settings/profile';
                $active1 = strpos($route, trim($url, '/')) === 0 ? ' active' : '';
                ?>
                <?= Html::a('การตั้งค่าโปรไฟล์', ['/user/settings/profile'], ['class' => 'nav-link d-flex py-75  ' . $active1]) ?>
            </li>
            <li class="nav-item">
                <?php
                $url = '/user/settings/account';
                $active2 = strpos($route, trim($url, '/')) === 0 ? ' active' : '';
                ?>
                <?= Html::a('การตั้งค่าบัญชี', ['/user/settings/account'], ['class' => 'nav-link d-flex py-75 ' . $active2]) ?>
            </li>
            <li class="nav-item">
                <?php
                $url = '/user/settings/networks';
                $active3 = strpos($route, trim($url, '/')) === 0 ? ' active' : '';
                ?>
                <?= Html::a('เชื่อมต่อเครือข่าย', ['/user/settings/networks'], ['class' => 'nav-link d-flex py-75 ' . $active3]) ?>
            </li>
        </ul>
    </div>
</div>
