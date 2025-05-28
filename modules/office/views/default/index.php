<?PHP

use app\components\Cdata;
use miloschuman\highcharts\Highcharts;
use app\components\Ccomponent;
use yii\helpers\Html;

$emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
$userProfile = Cdata::getDataUserAccount($emp->employee_cid);
$css = <<<CSS
.caption-style .card{
    overflow: hidden;
}

.caption-style .card:hover .overlay{
    opacity: 1;
}

.overlay {
    position: absolute;
    display: block;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0,0,0,0.5);
    z-index: 1;
    cursor: pointer;
    opacity: 0;
    -webkit-transition:all 0.35s ease-in-out;
    -moz-transition:all 0.35s ease-in-out;
    -o-transition:all 0.35s ease-in-out;
    -ms-transition:all 0.35s ease-in-out;
    transition:all 0.35s ease-in-out;
}


CSS;
$this->registerCss($css);

$js = <<<JS
$(".widget-caption").hover(
   function () {
    $('.widget-detail').addClass('bg-light');
  },
  function () {
    $(this .widget-detail).removeClass('bg-light');
  }
);

JS;
//$this->registerJs($js, $this::POS_READY);
$app = \Yii::$app;
$js = <<<JS
JS;
$this->registerJs($js, $this::POS_READY);
?>
<div class="row">
    <div class="col-xl-4 col-lg-6 col-sm-6">
        <div class="card overflow-hidden">
            <div class="card-body">
                <div class="text-center">
                    <div class="profile-photo">
                        <?= @Html::img($userProfile['pictureUrl'], ['class' => 'img-fluid rounded-circle', 'width' => '100']) ?>
                    </div>
                    <h3 class="mt-4 mb-1"><?= @$emp->employee_fullname ?></h3>
                    <p class="text-muted"><?= @$emp->position->employee_position_name ?></p>
                    <a class="btn btn-outline-primary btn-rounded mt-3 px-5" href="javascript:void();">Folllow</a>
                </div>
            </div>

            <div class="card-footer pt-0 pb-0 text-center">
                <div class="row">
                    <div class="col-4 pt-3 pb-3 border-end">
                        <h3 class="mb-1">150</h3><span>Follower</span>
                    </div>
                    <div class="col-4 pt-3 pb-3 border-end">
                        <h3 class="mb-1">140</h3><span>Place Stay</span>
                    </div>
                    <div class="col-4 pt-3 pb-3">
                        <h3 class="mb-1">45</h3><span>Reviews</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h2 class="card-title">ข้อมูลการลา</h2>
            </div>
            <div class="card-body pb-0">
                <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex px-0 justify-content-between">
                        <strong>Gender</strong>
                        <span class="mb-0">Male</span>
                    </li>
                    <li class="list-group-item d-flex px-0 justify-content-between">
                        <strong>Education</strong>
                        <span class="mb-0">PHD</span>
                    </li>
                    <li class="list-group-item d-flex px-0 justify-content-between">
                        <strong>Designation</strong>
                        <span class="mb-0">Se. Professor</span>
                    </li>
                    <li class="list-group-item d-flex px-0 justify-content-between">
                        <strong>Operation Done</strong>
                        <span class="mb-0">120</span>
                    </li>
                </ul>
            </div>
            <div class="card-footer pt-0 pb-0 text-center">
                <div class="row">
                    <div class="col-4 pt-3 pb-3 border-end">
                        <h3 class="mb-1 text-primary">150</h3>
                        <span>Projects</span>
                    </div>
                    <div class="col-4 pt-3 pb-3 border-end">
                        <h3 class="mb-1 text-primary">140</h3>
                        <span>Uploads</span>
                    </div>
                    <div class="col-4 pt-3 pb-3">
                        <h3 class="mb-1 text-primary">45</h3>
                        <span>Tasks</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-lg-12 col-sm-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <h4 class="fs-20 mb-0">Profile Stregth</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-xl-6 col-sm-6">
                        <div class="progress default-progress">
                            <div class="progress-bar bg-vigit progress-animated" style="width: 90%; height:13px;" role="progressbar">
                                <span class="sr-only">90% Complete</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                            <span class="fs-14 font-w500">Visitor</span>
                            <span class="fs-16"><span class="text-black pe-2"></span>90%</span>
                        </div>
                        <div class="progress default-progress">
                            <div class="progress-bar bg-contact progress-animated" style="width: 68%; height:13px;" role="progressbar">
                                <span class="sr-only">45% Complete</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                            <span class="fs-14 font-w500">Contact</span>
                            <span class="fs-16"><span class="text-black pe-2"></span>68%</span>
                        </div>
                        <div class="progress default-progress">
                            <div class="progress-bar bg-follow progress-animated" style="width: 85%; height:13px;" role="progressbar">
                                <span class="sr-only">85% Complete</span>
                            </div>
                        </div>
                        <div class="d-flex align-items-end mt-2 pb-4 justify-content-between">
                            <span class="fs-14 font-w500">Follow</span>
                            <span class="fs-16"><span class="text-black pe-2"></span>85%</span>
                        </div>
                    </div>
                    <div class="col-xl-6 col-sm-6" style="position: relative;">
                        <div id="pieChart3" style="min-height: 212.8px;">
                            <div id="apexchartsjx6zq79w" class="apexcharts-canvas apexchartsjx6zq79w apexcharts-theme-light" style="width: 307px; height: 212.8px;">
                                <svg id="SvgjsSvg1660" width="307" height="212.8" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1662" class="apexcharts-inner apexcharts-graphical" transform="translate(51.5, 0)"><defs id="SvgjsDefs1661"><clipPath id="gridRectMaskjx6zq79w"><rect id="SvgjsRect1664" width="210" height="228" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="gridRectMarkerMaskjx6zq79w"><rect id="SvgjsRect1665" width="210" height="232" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><filter id="SvgjsFilter1677" filterUnits="userSpaceOnUse" width="200%" height="200%" x="-50%" y="-50%"><feComponentTransfer id="SvgjsFeComponentTransfer1678" result="SvgjsFeComponentTransfer1678Out" in="SourceGraphic"><feFuncR id="SvgjsFeFuncR1679" type="linear" slope="0.5"></feFuncR><feFuncG id="SvgjsFeFuncG1680" type="linear" slope="0.5"></feFuncG><feFuncB id="SvgjsFeFuncB1681" type="linear" slope="0.5"></feFuncB><feFuncA id="SvgjsFeFuncA1682" type="identity"></feFuncA></feComponentTransfer></filter></defs><g id="SvgjsG1666" class="apexcharts-pie"><g id="SvgjsG1667" transform="translate(0, 0) scale(1)"><circle id="SvgjsCircle1668" r="62.71707317073171" cx="103" cy="103" fill="transparent"></circle><g id="SvgjsG1669" class="apexcharts-slices"><g id="SvgjsG1670" class="apexcharts-series apexcharts-pie-series" seriesName="seriesx1" rel="1" data:realIndex="0"><path id="SvgjsPath1671" d="M 103 6.512195121951208 A 96.48780487804879 96.48780487804879 0 0 1 173.1826860015361 169.213949253871 L 148.61874590099848 146.03906701501614 A 62.71707317073171 62.71707317073171 0 0 0 103 40.28292682926829 L 103 6.512195121951208 z" fill="rgba(246,173,46,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-0" index="0" j="0" data:angle="133.33333333333334" data:startAngle="0" data:strokeWidth="0" data:value="90" data:pathOrig="M 103 6.512195121951208 A 96.48780487804879 96.48780487804879 0 0 1 173.1826860015361 169.213949253871 L 148.61874590099848 146.03906701501614 A 62.71707317073171 62.71707317073171 0 0 0 103 40.28292682926829 L 103 6.512195121951208 z" data:pieClicked="false"></path></g><g id="SvgjsG1672" class="apexcharts-series apexcharts-pie-series" seriesName="seriesx2" rel="2" data:realIndex="1"><path id="SvgjsPath1673" d="M 173.1826860015361 169.213949253871 A 96.48780487804879 96.48780487804879 0 0 1 24.866469291264266 159.61314219482492 L 52.21320503932178 139.79854242663617 A 62.71707317073171 62.71707317073171 0 0 0 148.61874590099848 146.03906701501614 L 173.1826860015361 169.213949253871 z" fill="var(--primary)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-1" index="0" j="1" data:angle="100.74074074074073" data:startAngle="133.33333333333334" data:strokeWidth="0" data:value="68" data:pathOrig="M 173.1826860015361 169.213949253871 A 96.48780487804879 96.48780487804879 0 0 1 24.866469291264266 159.61314219482492 L 52.21320503932178 139.79854242663617 A 62.71707317073171 62.71707317073171 0 0 0 148.61874590099848 146.03906701501614 L 173.1826860015361 169.213949253871 z" data:pieClicked="false"></path></g><g id="SvgjsG1674" class="apexcharts-series apexcharts-pie-series" seriesName="seriesx3" rel="3" data:realIndex="2"><path id="SvgjsPath1675" d="M 21.703658126224795 162.06524974256072 A 100.48780487804879 100.48780487804879 0 0 1 101.24624598727786 2.527499901357743 L 101.90543614846754 40.292478943289154 A 62.71707317073171 62.71707317073171 0 0 0 52.26082196742099 139.86417067670405 L 21.703658126224795 162.06524974256072 z" fill="rgba(65,46,255,1)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-pie-area apexcharts-donut-slice-2" index="0" j="2" selected="true" filter="url(#SvgjsFilter1677)" data:angle="125.92592592592592" data:startAngle="234.07407407407408" data:strokeWidth="0" data:value="85" data:pathOrig="M 24.866469291264266 159.61314219482492 A 96.48780487804879 96.48780487804879 0 0 1 102.98315970125404 6.512196591544509 L 102.98905380581513 40.28292778450393 A 62.71707317073171 62.71707317073171 0 0 0 52.21320503932178 139.79854242663617 L 24.866469291264266 159.61314219482492 z" data:pieClicked="true"></path></g></g></g></g><line id="SvgjsLine1683" x1="0" y1="0" x2="206" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1684" x1="0" y1="0" x2="206" y2="0" stroke-dasharray="0" stroke-width="0" class="apexcharts-ycrosshairs-hidden"></line></g><g id="SvgjsG1663" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 115px;"></div><div class="apexcharts-tooltip apexcharts-theme-dark"><div class="apexcharts-tooltip-series-group" style="order: 1;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(246, 173, 46);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label"></span><span class="apexcharts-tooltip-text-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 2;"><span class="apexcharts-tooltip-marker" style="background-color: var(--primary);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label"></span><span class="apexcharts-tooltip-text-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div><div class="apexcharts-tooltip-series-group" style="order: 3;"><span class="apexcharts-tooltip-marker" style="background-color: rgb(65, 46, 255);"></span><div class="apexcharts-tooltip-text" style="font-family: Helvetica, Arial, sans-serif; font-size: 12px;"><div class="apexcharts-tooltip-y-group"><span class="apexcharts-tooltip-text-label"></span><span class="apexcharts-tooltip-text-value"></span></div><div class="apexcharts-tooltip-z-group"><span class="apexcharts-tooltip-text-z-label"></span><span class="apexcharts-tooltip-text-z-value"></span></div></div></div></div></div></div>
                        <div class="resize-triggers">
                            <div class="expand-trigger">
                                <div style="width: 338px; height: 214px;"></div></div><div class="contract-trigger"></div></div></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?PHP
/*
  echo Highcharts::widget([
  'options' => [
  'chart' => [
  'type' => 'column',
  ],
  'title' => ['text' => 'Fruit Consumption'],
  'xAxis' => [
  'categories' => ['Apples', 'Bananas', 'Oranges']
  ],
  'yAxis' => [
  'title' => ['text' => 'Fruit eaten']
  ],
  'series' => [
  ['name' => 'Jane', 'data' => [1, 0, 4]],
  ['name' => 'John', 'data' => [5, 7, 3]]
  ]
  ]
  ]);
 *
 */
?>


<div class="row">

    <div class="col-xl-12">
        <div class="mt-4 d-flex justify-content-between align-items-center flex-wrap1">
            <div class="mb-4">
                <h4 class="text-primary h3 font-weight-bold">HOSPITAL MANAGEMENT SYSTEM (HMS 2023) </h4>
                <p>ระบบสารบรรณอิเล็กทรอนิกส์สำหรับหน่วยงานภาครัฐ (e-Saraban)  ระบบให้บริการรับส่ง หนังสือ จัดเก็บเอกสาร เพื่อส่งต่อ สั่งการและลงนามในเอกสารหรือส่งเข้าระบบหนังสือเวียน ที่มีการลงนาม รับทราบ ผ่านระบบด้วยวิธีการทางอิเล็กทรอนิกส์</p>
            </div>
            <div class="d-flex align-items-center mb-4">
                <div class="default-tab job-tabs">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#Boxed">
                                <i class="fas fa-th-large"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-bs-toggle="tab" href="#List1">
                                <i class="fas fa-list"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <div>
                    <select class="default-select dashboard-select border-0 bg-transparent" style="display: none;">
                        <option data-display="newest">newest</option>

                        <option value="2">oldest</option>
                    </select><div class="nice-select default-select dashboard-select border-0 bg-transparent" tabindex="0"><span class="current">newest</span><ul class="list"><li data-value="newest" data-display="newest" class="option selected">newest</li><li data-value="2" class="option">oldest</li></ul></div>
                </div>
            </div>
        </div>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="Boxed" role="tabpanel">
                <div class="row">
                    <?PHP
                    $img = [
                        ['caption' => 'ลงรับ/ออกเลขหนังสือ', 'img' => 'https://t4.ftcdn.net/jpg/04/98/16/01/240_F_498160199_hUjslfoWaZBdprwHmz8VYyzXOYXarXfq.jpg'],
                        ['caption' => 'เขียนบันทึก/เสนอแฟ้ม', 'img' => 'https://t4.ftcdn.net/jpg/05/36/02/29/240_F_536022982_ZVAfmuTOe2RbKRGDwJyISXPnuxU93R1c.jpg'],
                        ['caption' => 'เขียนขออนุญาตไปราชการ', 'img' => 'https://as2.ftcdn.net/v2/jpg/02/51/70/05/1000_F_251700536_lhg0eNZec6oASF79XuSi6Hep3m9CyVpa.jpg'],
                        ['caption' => 'เขียนขออนุญาตลา', 'img' => 'https://t3.ftcdn.net/jpg/05/38/99/38/240_F_538993824_nP9WN2YKYaOyZMsLn2X5Q2s9bex04y01.jpg'],
                        ['caption' => 'จองห้องประชุม', 'img' => 'https://t3.ftcdn.net/jpg/05/35/41/48/240_F_535414849_4wID5OkYODI5mBWknIyuJ23eB1KjRYMB.jpg'],
                        ['caption' => 'ประเมินความพึงพอใจ', 'img' => 'https://t4.ftcdn.net/jpg/04/85/09/55/240_F_485095547_SEFnUTEscD7auyTTBqmhd7hfRA99sKPP.jpg'],
                    ];
                    for ($i = 0; $i < count($img); $i++) {
                        ?>
                        <div class="col-xl-2 col-xxl-3 col-md-3 col-sm-6 caption-style">
                            <div class="card">
                                <img src="<?= @$img[$i]['img'] ?>" class="card-img-top img-fluid" />
                                <div class="m-1" style="height: 50px;">
                                    <h6 class="card-title"><i class="fa-solid fa-circle-down"></i> <?= @$img[$i]['caption'] ?></h6>
                                </div>

                                <div class="card-footer d-sm-flex justify-content-between align-items-center">
                                    <div class="card-footer-link mb-4 mb-sm-0">
                                        <p class="card-text text-dark d-inline"></p>
                                    </div>
                                    <a href="javascript:void(0);" class="btn btn-primary btn-xs">ดำเนินการ</a>
                                </div>
                                <div class="overlay">
                                    <div class="text-center mt-5">
                                        <div class="text-white h5 font-weight-bold"><?= @$img[$i]['caption'] ?></div>
                                        <p class="text-white m-2 small"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?PHP
                    }
                    ?>
                </div>
            </div>
            <hr>
            <div class="tab-pane fade" id="List1">

            </div>
        </div>
    </div>

</div>



