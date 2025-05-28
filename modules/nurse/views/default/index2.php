<?PHP

use yii\bootstrap4\Html;
use app\assets\VuexyAsset;

$theme = VuexyAsset::register($this);
$path = $theme->baseUrl;
?>

<div class="row">
    <!-- Website Analytics -->
    <div class="col-lg-6 mb-4">
        <div class="swiper-container swiper-container-horizontal swiper swiper-card-advance-bg swiper-initialized swiper-horizontal swiper-pointer-events swiper-backface-hidden" id="swiper-with-pagination-cards">
            <div class="swiper-wrapper" id="swiper-wrapper-10a7dd43158344100" aria-live="off" style="transform: translate3d(-2052px, 0px, 0px); transition-duration: 0ms;"><div class="swiper-slide swiper-slide-duplicate swiper-slide-duplicate-active" data-swiper-slide-index="2" role="group" aria-label="3 / 3" style="width: 684px;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Website Analytics</h5>
                            <small>Total 28.5% Conversion Rate</small>
                        </div>
                        <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                            <h6 class="text-white mt-0 mt-md-3 mb-3">Revenue Sources</h6>
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">268</p>
                                            <p class="mb-0">Direct</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">62</p>
                                            <p class="mb-0">Referral</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">890</p>
                                            <p class="mb-0">Organic</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
                                            <p class="mb-0">Campaign</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                            <img src="<?= $path ?>/img/illustrations/card-website-analytics-3.png" alt="Website Analytics" width="170" class="card-website-analytics-img">
                        </div>
                    </div>
                </div>
                <div class="swiper-slide swiper-slide-duplicate-next" data-swiper-slide-index="0" role="group" aria-label="1 / 3" style="width: 684px;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Website Analytics</h5>
                            <small>Total 28.5% Conversion Rate</small>
                        </div>
                        <div class="row">
                            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                <h6 class="text-white mt-0 mt-md-3 mb-3">Traffic</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">28%</p>
                                                <p class="mb-0">Sessions</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
                                                <p class="mb-0">Leads</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">3.1k</p>
                                                <p class="mb-0">Page Views</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12%</p>
                                                <p class="mb-0">Conversions</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                <img src="<?= $path ?>/img/illustrations/card-website-analytics-1.png" alt="Website Analytics" width="170" class="card-website-analytics-img">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-slide swiper-slide-prev" data-swiper-slide-index="1" role="group" aria-label="2 / 3" style="width: 684px;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Website Analytics</h5>
                            <small>Total 28.5% Conversion Rate</small>
                        </div>
                        <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                            <h6 class="text-white mt-0 mt-md-3 mb-3">Spending</h6>
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12h</p>
                                            <p class="mb-0">Spend</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">127</p>
                                            <p class="mb-0">Order</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">18</p>
                                            <p class="mb-0">Order Size</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">2.3k</p>
                                            <p class="mb-0">Items</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                            <img src="<?= $path ?>/img/illustrations/card-website-analytics-2.png" alt="Website Analytics" width="170" class="card-website-analytics-img">
                        </div>
                    </div>
                </div>
                <div class="swiper-slide swiper-slide-active" data-swiper-slide-index="2" role="group" aria-label="3 / 3" style="width: 684px;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Website Analytics</h5>
                            <small>Total 28.5% Conversion Rate</small>
                        </div>
                        <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                            <h6 class="text-white mt-0 mt-md-3 mb-3">Revenue Sources</h6>
                            <div class="row">
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">268</p>
                                            <p class="mb-0">Direct</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">62</p>
                                            <p class="mb-0">Referral</p>
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-6">
                                    <ul class="list-unstyled mb-0">
                                        <li class="d-flex mb-4 align-items-center">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">890</p>
                                            <p class="mb-0">Organic</p>
                                        </li>
                                        <li class="d-flex align-items-center mb-2">
                                            <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
                                            <p class="mb-0">Campaign</p>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                            <img src="<?= $path ?>/img/illustrations/card-website-analytics-3.png" alt="Website Analytics" width="170" class="card-website-analytics-img">
                        </div>
                    </div>
                </div>
                <div class="swiper-slide swiper-slide-duplicate swiper-slide-next" data-swiper-slide-index="0" role="group" aria-label="1 / 3" style="width: 684px;">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="text-white mb-0 mt-2">Website Analytics</h5>
                            <small>Total 28.5% Conversion Rate</small>
                        </div>
                        <div class="row">
                            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                                <h6 class="text-white mt-0 mt-md-3 mb-3">Traffic</h6>
                                <div class="row">
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">28%</p>
                                                <p class="mb-0">Sessions</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">1.2k</p>
                                                <p class="mb-0">Leads</p>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-6">
                                        <ul class="list-unstyled mb-0">
                                            <li class="d-flex mb-4 align-items-center">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">3.1k</p>
                                                <p class="mb-0">Page Views</p>
                                            </li>
                                            <li class="d-flex align-items-center mb-2">
                                                <p class="mb-0 fw-semibold me-2 website-analytics-text-bg">12%</p>
                                                <p class="mb-0">Conversions</p>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-4 my-md-0 text-center">
                                <img src="<?= $path ?>/img/illustrations/card-website-analytics-1.png" alt="Website Analytics" width="170" class="card-website-analytics-img">
                            </div>
                        </div>
                    </div>
                </div></div>
            <div class="swiper-pagination swiper-pagination-clickable swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 1"></span><span class="swiper-pagination-bullet" tabindex="0" role="button" aria-label="Go to slide 2"></span><span class="swiper-pagination-bullet swiper-pagination-bullet-active" tabindex="0" role="button" aria-label="Go to slide 3" aria-current="true"></span></div>
            <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
    </div>
    <!--/ Website Analytics -->

    <!-- Sales Overview -->
    <div class="col-lg-3 col-sm-6 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <small class="d-block mb-1 text-muted">Sales Overview</small>
                    <p class="card-text text-success">+18.2%</p>
                </div>
                <h4 class="card-title mb-1">$42.5k</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="d-flex gap-2 align-items-center mb-2">
                            <span class="badge bg-label-info p-1 rounded"><i class="ti ti-shopping-cart ti-xs"></i></span>
                            <p class="mb-0">Order</p>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap">62.2%</h5>
                        <small class="text-muted">6,440</small>
                    </div>
                    <div class="col-4">
                        <div class="divider divider-vertical">
                            <div class="divider-text">
                                <span class="badge-divider-bg bg-label-secondary">VS</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-4 text-end">
                        <div class="d-flex gap-2 justify-content-end align-items-center mb-2">
                            <p class="mb-0">Visits</p>
                            <span class="badge bg-label-primary p-1 rounded"><i class="ti ti-link ti-xs"></i></span>
                        </div>
                        <h5 class="mb-0 pt-1 text-nowrap ms-lg-n3 ms-xl-0">25.5%</h5>
                        <small class="text-muted">12,749</small>
                    </div>
                </div>
                <div class="d-flex align-items-center mt-4">
                    <div class="progress w-100" style="height: 8px;">
                        <div class="progress-bar bg-info" style="width: 70%" role="progressbar" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100"></div>
                        <div class="progress-bar bg-primary" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Sales Overview -->

    <!-- Revenue Generated -->
    <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
        <div class="card">
            <div class="card-body pb-0">
                <div class="card-icon">
                    <span class="badge bg-label-success rounded-pill p-2">
                        <i class="ti ti-credit-card ti-sm"></i>
                    </span>
                </div>
                <h5 class="card-title mb-0 mt-2">97.5k</h5>
                <small>Revenue Generated</small>
            </div>
            <div id="revenueGenerated" style="min-height: 130px;"><div id="apexcharts9e8zghsk" class="apexcharts-canvas apexcharts9e8zghsk apexcharts-theme-light" style="width: 330px; height: 130px;"><svg id="SvgjsSvg1001" width="330" height="130" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1003" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1002"><clipPath id="gridRectMask9e8zghsk"><rect id="SvgjsRect1008" width="336" height="132" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask9e8zghsk"></clipPath><clipPath id="nonForecastMask9e8zghsk"></clipPath><clipPath id="gridRectMarkerMask9e8zghsk"><rect id="SvgjsRect1009" width="334" height="134" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1014" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1015" stop-opacity="0.6" stop-color="rgba(40,199,111,0.6)" offset="0"></stop><stop id="SvgjsStop1016" stop-opacity="0.1" stop-color="rgba(212,244,226,0.1)" offset="1"></stop><stop id="SvgjsStop1017" stop-opacity="0.1" stop-color="rgba(212,244,226,0.1)" offset="1"></stop></linearGradient></defs><line id="SvgjsLine1007" x1="0" y1="0" x2="0" y2="130" stroke="#b6b6b6" stroke-dasharray="3" stroke-linecap="butt" class="apexcharts-xcrosshairs" x="0" y="0" width="1" height="130" fill="#b1b9c4" filter="none" fill-opacity="0.9" stroke-width="1"></line><g id="SvgjsG1020" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1021" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1030" class="apexcharts-grid"><g id="SvgjsG1031" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1033" x1="0" y1="0" x2="330" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1034" x1="0" y1="26" x2="330" y2="26" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1035" x1="0" y1="52" x2="330" y2="52" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1036" x1="0" y1="78" x2="330" y2="78" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1037" x1="0" y1="104" x2="330" y2="104" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1038" x1="0" y1="130" x2="330" y2="130" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1032" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1040" x1="0" y1="130" x2="330" y2="130" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1039" x1="0" y1="1" x2="0" y2="130" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1010" class="apexcharts-area-series apexcharts-plot-series"><g id="SvgjsG1011" class="apexcharts-series" seriesName="seriesx1" data:longestSeries="true" rel="1" data:realIndex="0"><path id="SvgjsPath1018" d="M0 130L0 104C19.25 104 35.75 60.66666666666663 55 60.66666666666663C74.25 60.66666666666663 90.75 78 110 78C129.25 78 145.75 34.66666666666663 165 34.66666666666663C184.25 34.66666666666663 200.75 69.33333333333331 220 69.33333333333331C239.25 69.33333333333331 255.75 17.333333333333314 275 17.333333333333314C294.25 17.333333333333314 310.75 34.66666666666663 330 34.66666666666663C330 34.66666666666663 330 34.66666666666663 330 130M330 34.66666666666663C330 34.66666666666663 330 34.66666666666663 330 34.66666666666663 " fill="url(#SvgjsLinearGradient1014)" fill-opacity="1" stroke-opacity="1" stroke-linecap="butt" stroke-width="0" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask9e8zghsk)" pathTo="M 0 130L 0 104C 19.25 104 35.75 60.66666666666663 55 60.66666666666663C 74.25 60.66666666666663 90.75 78 110 78C 129.25 78 145.75 34.66666666666663 165 34.66666666666663C 184.25 34.66666666666663 200.75 69.33333333333331 220 69.33333333333331C 239.25 69.33333333333331 255.75 17.333333333333314 275 17.333333333333314C 294.25 17.333333333333314 310.75 34.66666666666663 330 34.66666666666663C 330 34.66666666666663 330 34.66666666666663 330 130M 330 34.66666666666663z" pathFrom="M -1 364L -1 364L 55 364L 110 364L 165 364L 220 364L 275 364L 330 364"></path><path id="SvgjsPath1019" d="M0 104C19.25 104 35.75 60.66666666666663 55 60.66666666666663C74.25 60.66666666666663 90.75 78 110 78C129.25 78 145.75 34.66666666666663 165 34.66666666666663C184.25 34.66666666666663 200.75 69.33333333333331 220 69.33333333333331C239.25 69.33333333333331 255.75 17.333333333333314 275 17.333333333333314C294.25 17.333333333333314 310.75 34.66666666666663 330 34.66666666666663C330 34.66666666666663 330 34.66666666666663 330 34.66666666666663 " fill="none" fill-opacity="1" stroke="#28c76f" stroke-opacity="1" stroke-linecap="butt" stroke-width="2" stroke-dasharray="0" class="apexcharts-area" index="0" clip-path="url(#gridRectMask9e8zghsk)" pathTo="M 0 104C 19.25 104 35.75 60.66666666666663 55 60.66666666666663C 74.25 60.66666666666663 90.75 78 110 78C 129.25 78 145.75 34.66666666666663 165 34.66666666666663C 184.25 34.66666666666663 200.75 69.33333333333331 220 69.33333333333331C 239.25 69.33333333333331 255.75 17.333333333333314 275 17.333333333333314C 294.25 17.333333333333314 310.75 34.66666666666663 330 34.66666666666663" pathFrom="M -1 364L -1 364L 55 364L 110 364L 165 364L 220 364L 275 364L 330 364"></path><g id="SvgjsG1012" class="apexcharts-series-markers-wrap" data:realIndex="0"></g></g><g id="SvgjsG1013" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1041" x1="0" y1="0" x2="330" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1042" x1="0" y1="0" x2="330" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1043" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1044" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1045" class="apexcharts-point-annotations"></g></g><rect id="SvgjsRect1006" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe"></rect><g id="SvgjsG1029" class="apexcharts-yaxis" rel="0" transform="translate(-18, 0)"></g><g id="SvgjsG1004" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 65px;"></div></div></div>
            <div class="resize-triggers"><div class="expand-trigger"><div style="width: 331px; height: 248px;"></div></div><div class="contract-trigger"></div></div></div>
    </div>
    <!--/ Revenue Generated -->

    <!-- Earning Reports -->
    <div class="col-lg-6 mb-4">
        <div class="card h-100">
            <div class="card-header pb-0 d-flex justify-content-between mb-lg-n4">
                <div class="card-title mb-0">
                    <h5 class="mb-0">Earning Reports</h5>
                    <small class="text-muted">Weekly Earnings Overview</small>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="earningReportsId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="earningReportsId">
                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                    </div>
                </div>
                <!-- </div> -->
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-4 d-flex flex-column align-self-end">
                        <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
                            <h1 class="mb-0">$468</h1>
                            <div class="badge rounded bg-label-success">+4.2%</div>
                        </div>
                        <small class="text-muted">You informed of this week compared to last week</small>
                    </div>
                    <div class="col-12 col-md-8" style="position: relative;">
                        <div id="weeklyEarningReports" style="min-height: 202px;"><div id="apexcharts9wqs2h3h" class="apexcharts-canvas apexcharts9wqs2h3h apexcharts-theme-light" style="width: 416px; height: 202px;"><svg id="SvgjsSvg1046" width="416" height="202" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1048" class="apexcharts-inner apexcharts-graphical" transform="translate(0, 0)"><defs id="SvgjsDefs1047"><linearGradient id="SvgjsLinearGradient1051" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1052" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop1053" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop1054" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMask9wqs2h3h"><rect id="SvgjsRect1056" width="430" height="163.46545538711547" x="-2" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMask9wqs2h3h"></clipPath><clipPath id="nonForecastMask9wqs2h3h"></clipPath><clipPath id="gridRectMarkerMask9wqs2h3h"><rect id="SvgjsRect1057" width="430" height="167.46545538711547" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect1055" width="0" height="163.46545538711547" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1051)" class="apexcharts-xcrosshairs" y2="163.46545538711547" filter="none" fill-opacity="0.9"></rect><g id="SvgjsG1076" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1077" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"><text id="SvgjsText1079" font-family="Public Sans" x="30.428571428571427" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1080">Mo</tspan><title>Mo</title></text><text id="SvgjsText1082" font-family="Public Sans" x="91.28571428571428" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1083">Tu</tspan><title>Tu</title></text><text id="SvgjsText1085" font-family="Public Sans" x="152.14285714285714" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1086">We</tspan><title>We</title></text><text id="SvgjsText1088" font-family="Public Sans" x="213" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1089">Th</tspan><title>Th</title></text><text id="SvgjsText1091" font-family="Public Sans" x="273.85714285714283" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1092">Fr</tspan><title>Fr</title></text><text id="SvgjsText1094" font-family="Public Sans" x="334.71428571428567" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1095">Sa</tspan><title>Sa</title></text><text id="SvgjsText1097" font-family="Public Sans" x="395.5714285714285" y="192.46545538711547" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="400" fill="#a5a3ae" class="apexcharts-text apexcharts-xaxis-label " style="font-family: &quot;Public Sans&quot;;"><tspan id="SvgjsTspan1098">Su</tspan><title>Su</title></text></g></g><g id="SvgjsG1101" class="apexcharts-grid"><g id="SvgjsG1102" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1104" x1="0" y1="0" x2="426" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1105" x1="0" y1="32.69309107742309" x2="426" y2="32.69309107742309" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1106" x1="0" y1="65.38618215484618" x2="426" y2="65.38618215484618" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1107" x1="0" y1="98.07927323226927" x2="426" y2="98.07927323226927" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1108" x1="0" y1="130.77236430969236" x2="426" y2="130.77236430969236" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1109" x1="0" y1="163.46545538711547" x2="426" y2="163.46545538711547" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1103" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1111" x1="0" y1="163.46545538711547" x2="426" y2="163.46545538711547" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1110" x1="0" y1="1" x2="0" y2="163.46545538711547" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1058" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG1059" class="apexcharts-series" rel="1" seriesName="seriesx1" data:realIndex="0"><path id="SvgjsPath1063" d="M18.865714285714283 159.46545538711547L18.865714285714283 102.07927323226929C18.865714285714283 99.41260656560263 20.199047619047615 98.07927323226929 22.865714285714283 98.07927323226929L37.99142857142857 98.07927323226929C40.658095238095235 98.07927323226929 41.99142857142857 99.41260656560263 41.99142857142857 102.07927323226929L41.99142857142857 102.07927323226929L41.99142857142857 159.46545538711547C41.99142857142857 162.13212205378213 40.658095238095235 163.46545538711547 37.99142857142857 163.46545538711547C37.99142857142857 163.46545538711547 22.865714285714283 163.46545538711547 22.865714285714283 163.46545538711547C20.199047619047615 163.46545538711547 18.865714285714283 162.13212205378213 18.865714285714283 159.46545538711547C18.865714285714283 159.46545538711547 18.865714285714283 159.46545538711547 18.865714285714283 159.46545538711547 " fill="#7367f029" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 18.865714285714283 159.46545538711547L 18.865714285714283 102.07927323226929Q 18.865714285714283 98.07927323226929 22.865714285714283 98.07927323226929L 37.99142857142857 98.07927323226929Q 41.99142857142857 98.07927323226929 41.99142857142857 102.07927323226929L 41.99142857142857 102.07927323226929L 41.99142857142857 159.46545538711547Q 41.99142857142857 163.46545538711547 37.99142857142857 163.46545538711547L 22.865714285714283 163.46545538711547Q 18.865714285714283 163.46545538711547 18.865714285714283 159.46545538711547z" pathFrom="M 18.865714285714283 159.46545538711547L 18.865714285714283 159.46545538711547L 41.99142857142857 159.46545538711547L 41.99142857142857 159.46545538711547L 41.99142857142857 159.46545538711547L 41.99142857142857 159.46545538711547L 41.99142857142857 159.46545538711547L 18.865714285714283 159.46545538711547" cy="98.07927323226929" cx="79.72285714285714" j="0" val="40" barHeight="65.38618215484618" barWidth="23.125714285714285"></path><path id="SvgjsPath1065" d="M79.72285714285714 159.46545538711547L79.72285714285714 61.21290938549042C79.72285714285714 58.54624271882375 81.05619047619047 57.21290938549042 83.72285714285714 57.21290938549042L98.84857142857142 57.21290938549042C101.51523809523809 57.21290938549042 102.84857142857142 58.54624271882375 102.84857142857142 61.21290938549042L102.84857142857142 61.21290938549042L102.84857142857142 159.46545538711547C102.84857142857142 162.13212205378213 101.51523809523809 163.46545538711547 98.84857142857142 163.46545538711547C98.84857142857142 163.46545538711547 83.72285714285714 163.46545538711547 83.72285714285714 163.46545538711547C81.05619047619047 163.46545538711547 79.72285714285714 162.13212205378213 79.72285714285714 159.46545538711547C79.72285714285714 159.46545538711547 79.72285714285714 159.46545538711547 79.72285714285714 159.46545538711547 " fill="#7367f029" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 79.72285714285714 159.46545538711547L 79.72285714285714 61.21290938549042Q 79.72285714285714 57.21290938549042 83.72285714285714 57.21290938549042L 98.84857142857142 57.21290938549042Q 102.84857142857142 57.21290938549042 102.84857142857142 61.21290938549042L 102.84857142857142 61.21290938549042L 102.84857142857142 159.46545538711547Q 102.84857142857142 163.46545538711547 98.84857142857142 163.46545538711547L 83.72285714285714 163.46545538711547Q 79.72285714285714 163.46545538711547 79.72285714285714 159.46545538711547z" pathFrom="M 79.72285714285714 159.46545538711547L 79.72285714285714 159.46545538711547L 102.84857142857142 159.46545538711547L 102.84857142857142 159.46545538711547L 102.84857142857142 159.46545538711547L 102.84857142857142 159.46545538711547L 102.84857142857142 159.46545538711547L 79.72285714285714 159.46545538711547" cy="57.21290938549042" cx="140.57999999999998" j="1" val="65" barHeight="106.25254600162505" barWidth="23.125714285714285"></path><path id="SvgjsPath1067" d="M140.57999999999998 159.46545538711547L140.57999999999998 85.73272769355773C140.57999999999998 83.06606102689106 141.9133333333333 81.73272769355773 144.57999999999998 81.73272769355773L159.70571428571427 81.73272769355773C162.37238095238092 81.73272769355773 163.70571428571427 83.06606102689106 163.70571428571427 85.73272769355773L163.70571428571427 85.73272769355773L163.70571428571427 159.46545538711547C163.70571428571427 162.13212205378213 162.37238095238092 163.46545538711547 159.70571428571427 163.46545538711547C159.70571428571427 163.46545538711547 144.57999999999998 163.46545538711547 144.57999999999998 163.46545538711547C141.9133333333333 163.46545538711547 140.57999999999998 162.13212205378213 140.57999999999998 159.46545538711547C140.57999999999998 159.46545538711547 140.57999999999998 159.46545538711547 140.57999999999998 159.46545538711547 " fill="#7367f029" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 140.57999999999998 159.46545538711547L 140.57999999999998 85.73272769355773Q 140.57999999999998 81.73272769355773 144.57999999999998 81.73272769355773L 159.70571428571427 81.73272769355773Q 163.70571428571427 81.73272769355773 163.70571428571427 85.73272769355773L 163.70571428571427 85.73272769355773L 163.70571428571427 159.46545538711547Q 163.70571428571427 163.46545538711547 159.70571428571427 163.46545538711547L 144.57999999999998 163.46545538711547Q 140.57999999999998 163.46545538711547 140.57999999999998 159.46545538711547z" pathFrom="M 140.57999999999998 159.46545538711547L 140.57999999999998 159.46545538711547L 163.70571428571427 159.46545538711547L 163.70571428571427 159.46545538711547L 163.70571428571427 159.46545538711547L 163.70571428571427 159.46545538711547L 163.70571428571427 159.46545538711547L 140.57999999999998 159.46545538711547" cy="81.73272769355773" cx="201.43714285714285" j="2" val="50" barHeight="81.73272769355773" barWidth="23.125714285714285"></path><path id="SvgjsPath1069" d="M201.43714285714285 159.46545538711547L201.43714285714285 93.90600046291351C201.43714285714282 91.23933379624684 202.7704761904762 89.90600046291351 205.43714285714285 89.90600046291351L220.56285714285713 89.90600046291351C223.2295238095238 89.90600046291351 224.56285714285713 91.23933379624684 224.56285714285713 93.90600046291351L224.56285714285713 93.90600046291351L224.56285714285713 159.46545538711547C224.56285714285713 162.13212205378213 223.2295238095238 163.46545538711547 220.56285714285713 163.46545538711547C220.56285714285713 163.46545538711547 205.43714285714285 163.46545538711547 205.43714285714285 163.46545538711547C202.7704761904762 163.46545538711547 201.43714285714282 162.13212205378213 201.43714285714285 159.46545538711547C201.43714285714285 159.46545538711547 201.43714285714285 159.46545538711547 201.43714285714285 159.46545538711547 " fill="#7367f029" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 201.43714285714285 159.46545538711547L 201.43714285714285 93.90600046291351Q 201.43714285714285 89.90600046291351 205.43714285714285 89.90600046291351L 220.56285714285713 89.90600046291351Q 224.56285714285713 89.90600046291351 224.56285714285713 93.90600046291351L 224.56285714285713 93.90600046291351L 224.56285714285713 159.46545538711547Q 224.56285714285713 163.46545538711547 220.56285714285713 163.46545538711547L 205.43714285714285 163.46545538711547Q 201.43714285714285 163.46545538711547 201.43714285714285 159.46545538711547z" pathFrom="M 201.43714285714285 159.46545538711547L 201.43714285714285 159.46545538711547L 224.56285714285713 159.46545538711547L 224.56285714285713 159.46545538711547L 224.56285714285713 159.46545538711547L 224.56285714285713 159.46545538711547L 224.56285714285713 159.46545538711547L 201.43714285714285 159.46545538711547" cy="89.90600046291351" cx="262.2942857142857" j="3" val="45" barHeight="73.55945492420196" barWidth="23.125714285714285"></path><path id="SvgjsPath1071" d="M262.2942857142857 159.46545538711547L262.2942857142857 20.346545538711553C262.2942857142857 17.679878872044895 263.627619047619 16.346545538711553 266.2942857142857 16.346545538711553L281.42 16.346545538711553C284.0866666666667 16.346545538711553 285.42 17.679878872044895 285.42 20.346545538711553L285.42 20.346545538711553L285.42 159.46545538711547C285.42 162.13212205378213 284.0866666666667 163.46545538711547 281.42 163.46545538711547C281.42 163.46545538711547 266.2942857142857 163.46545538711547 266.2942857142857 163.46545538711547C263.627619047619 163.46545538711547 262.2942857142857 162.13212205378213 262.2942857142857 159.46545538711547C262.2942857142857 159.46545538711547 262.2942857142857 159.46545538711547 262.2942857142857 159.46545538711547 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 262.2942857142857 159.46545538711547L 262.2942857142857 20.346545538711553Q 262.2942857142857 16.346545538711553 266.2942857142857 16.346545538711553L 281.42 16.346545538711553Q 285.42 16.346545538711553 285.42 20.346545538711553L 285.42 20.346545538711553L 285.42 159.46545538711547Q 285.42 163.46545538711547 281.42 163.46545538711547L 266.2942857142857 163.46545538711547Q 262.2942857142857 163.46545538711547 262.2942857142857 159.46545538711547z" pathFrom="M 262.2942857142857 159.46545538711547L 262.2942857142857 159.46545538711547L 285.42 159.46545538711547L 285.42 159.46545538711547L 285.42 159.46545538711547L 285.42 159.46545538711547L 285.42 159.46545538711547L 262.2942857142857 159.46545538711547" cy="16.346545538711553" cx="323.15142857142854" j="4" val="90" barHeight="147.11890984840392" barWidth="23.125714285714285"></path><path id="SvgjsPath1073" d="M323.15142857142854 159.46545538711547L323.15142857142854 77.55945492420197C323.15142857142854 74.8927882575353 324.48476190476185 73.55945492420197 327.15142857142854 73.55945492420197L342.27714285714285 73.55945492420197C344.9438095238095 73.55945492420197 346.27714285714285 74.8927882575353 346.27714285714285 77.55945492420197L346.27714285714285 77.55945492420197L346.27714285714285 159.46545538711547C346.27714285714285 162.13212205378213 344.9438095238095 163.46545538711547 342.27714285714285 163.46545538711547C342.27714285714285 163.46545538711547 327.15142857142854 163.46545538711547 327.15142857142854 163.46545538711547C324.48476190476185 163.46545538711547 323.15142857142854 162.13212205378213 323.15142857142854 159.46545538711547C323.15142857142854 159.46545538711547 323.15142857142854 159.46545538711547 323.15142857142854 159.46545538711547 " fill="#7367f029" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 323.15142857142854 159.46545538711547L 323.15142857142854 77.55945492420197Q 323.15142857142854 73.55945492420197 327.15142857142854 73.55945492420197L 342.27714285714285 73.55945492420197Q 346.27714285714285 73.55945492420197 346.27714285714285 77.55945492420197L 346.27714285714285 77.55945492420197L 346.27714285714285 159.46545538711547Q 346.27714285714285 163.46545538711547 342.27714285714285 163.46545538711547L 327.15142857142854 163.46545538711547Q 323.15142857142854 163.46545538711547 323.15142857142854 159.46545538711547z" pathFrom="M 323.15142857142854 159.46545538711547L 323.15142857142854 159.46545538711547L 346.27714285714285 159.46545538711547L 346.27714285714285 159.46545538711547L 346.27714285714285 159.46545538711547L 346.27714285714285 159.46545538711547L 346.27714285714285 159.46545538711547L 323.15142857142854 159.46545538711547" cy="73.55945492420197" cx="384.0085714285714" j="5" val="55" barHeight="89.9060004629135" barWidth="23.125714285714285"></path><path id="SvgjsPath1075" d="M384.0085714285714 159.46545538711547L384.0085714285714 53.03963661613464C384.0085714285714 50.37296994946797 385.34190476190474 49.03963661613464 388.0085714285714 49.03963661613464L403.1342857142857 49.03963661613464C405.80095238095237 49.03963661613464 407.13428571428574 50.37296994946797 407.1342857142857 53.03963661613464L407.1342857142857 53.03963661613464L407.1342857142857 159.46545538711547C407.13428571428574 162.13212205378213 405.80095238095237 163.46545538711547 403.1342857142857 163.46545538711547C403.1342857142857 163.46545538711547 388.0085714285714 163.46545538711547 388.0085714285714 163.46545538711547C385.34190476190474 163.46545538711547 384.0085714285714 162.13212205378213 384.0085714285714 159.46545538711547C384.0085714285714 159.46545538711547 384.0085714285714 159.46545538711547 384.0085714285714 159.46545538711547 " fill="#7367f029" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMask9wqs2h3h)" pathTo="M 384.0085714285714 159.46545538711547L 384.0085714285714 53.03963661613464Q 384.0085714285714 49.03963661613464 388.0085714285714 49.03963661613464L 403.1342857142857 49.03963661613464Q 407.1342857142857 49.03963661613464 407.1342857142857 53.03963661613464L 407.1342857142857 53.03963661613464L 407.1342857142857 159.46545538711547Q 407.1342857142857 163.46545538711547 403.1342857142857 163.46545538711547L 388.0085714285714 163.46545538711547Q 384.0085714285714 163.46545538711547 384.0085714285714 159.46545538711547z" pathFrom="M 384.0085714285714 159.46545538711547L 384.0085714285714 159.46545538711547L 407.1342857142857 159.46545538711547L 407.1342857142857 159.46545538711547L 407.1342857142857 159.46545538711547L 407.1342857142857 159.46545538711547L 407.1342857142857 159.46545538711547L 384.0085714285714 159.46545538711547" cy="49.03963661613464" cx="444.8657142857142" j="6" val="70" barHeight="114.42581877098083" barWidth="23.125714285714285"></path><g id="SvgjsG1061" class="apexcharts-bar-goals-markers" style="pointer-events: none"><g id="SvgjsG1062" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1064" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1066" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1068" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1070" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1072" className="apexcharts-bar-goals-groups"></g><g id="SvgjsG1074" className="apexcharts-bar-goals-groups"></g></g></g><g id="SvgjsG1060" class="apexcharts-datalabels" data:realIndex="0"></g></g><line id="SvgjsLine1112" x1="0" y1="0" x2="426" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1113" x1="0" y1="0" x2="426" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1114" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1115" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1116" class="apexcharts-point-annotations"></g></g><g id="SvgjsG1099" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)"><g id="SvgjsG1100" class="apexcharts-yaxis-texts-g"></g></g><g id="SvgjsG1049" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 101px;"></div></div></div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 441px; height: 203px;"></div></div><div class="contract-trigger"></div></div></div>
                </div>
                <div class="border rounded p-3 mt-2">
                    <div class="row gap-4 gap-sm-0">
                        <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-currency-dollar ti-sm"></i></div>
                                <h6 class="mb-0">Earnings</h6>
                            </div>
                            <h4 class="my-2 pt-1">$545.69</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-chart-pie-2 ti-sm"></i></div>
                                <h6 class="mb-0">Profit</h6>
                            </div>
                            <h4 class="my-2 pt-1">$256.34</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <div class="col-12 col-sm-4">
                            <div class="d-flex gap-2 align-items-center">
                                <div class="badge rounded bg-label-danger p-1"><i class="ti ti-brand-paypal ti-sm"></i></div>
                                <h6 class="mb-0">Expense</h6>
                            </div>
                            <h4 class="my-2 pt-1">$74.19</h4>
                            <div class="progress w-75" style="height:4px">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Earning Reports -->

    <!-- Support Tracker -->
    <div class="col-md-6 mb-4">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-0">
                <div class="card-title mb-0">
                    <h5 class="mb-0">Support Tracker</h5>
                    <small class="text-muted">Last 7 Days</small>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="supportTrackerMenu" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="supportTrackerMenu">
                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-4 col-md-12 col-lg-4">
                        <div class="mt-lg-4 mt-lg-2 mb-lg-4 mb-2 pt-1">
                            <h1 class="mb-0">164</h1>
                            <p class="mb-0">Total Tickets</p>
                        </div>
                        <ul class="p-0 m-0">
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pt-2 pb-1">
                                <div class="badge rounded bg-label-primary p-1"><i class="ti ti-ticket ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">New Tickets</h6>
                                    <small class="text-muted">142</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center mb-lg-3 pb-1">
                                <div class="badge rounded bg-label-info p-1"><i class="ti ti-circle-check ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Open Tickets</h6>
                                    <small class="text-muted">28</small>
                                </div>
                            </li>
                            <li class="d-flex gap-3 align-items-center pb-1">
                                <div class="badge rounded bg-label-warning p-1"><i class="ti ti-clock ti-sm"></i></div>
                                <div>
                                    <h6 class="mb-0 text-nowrap">Response Time</h6>
                                    <small class="text-muted">1 Day</small>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="col-12 col-sm-8 col-md-12 col-lg-8" style="position: relative;">
                        <div id="supportTracker" style="min-height: 280.637px;"><div id="apexchartssesp3r1kf" class="apexcharts-canvas apexchartssesp3r1kf apexcharts-theme-light" style="width: 416px; height: 280.637px;"><svg id="SvgjsSvg1117" width="416" height="280.6365966796875" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1119" class="apexcharts-inner apexcharts-graphical" transform="translate(41, -10)"><defs id="SvgjsDefs1118"><clipPath id="gridRectMasksesp3r1kf"><rect id="SvgjsRect1121" width="342" height="375" x="-3" y="-1" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMasksesp3r1kf"></clipPath><clipPath id="nonForecastMasksesp3r1kf"></clipPath><clipPath id="gridRectMarkerMasksesp3r1kf"><rect id="SvgjsRect1122" width="340" height="377" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><linearGradient id="SvgjsLinearGradient1127" x1="1" y1="0" x2="0" y2="1"><stop id="SvgjsStop1128" stop-opacity="1" stop-color="rgba(115,103,240,1)" offset="0.3"></stop><stop id="SvgjsStop1129" stop-opacity="0.6" stop-color="rgba(255,255,255,0.6)" offset="0.7"></stop><stop id="SvgjsStop1130" stop-opacity="0.6" stop-color="rgba(255,255,255,0.6)" offset="1"></stop></linearGradient><linearGradient id="SvgjsLinearGradient1138" x1="1" y1="0" x2="0" y2="1"><stop id="SvgjsStop1139" stop-opacity="1" stop-color="rgba(115,103,240,1)" offset="0.3"></stop><stop id="SvgjsStop1140" stop-opacity="0.6" stop-color="rgba(115,103,240,0.6)" offset="0.7"></stop><stop id="SvgjsStop1141" stop-opacity="0.6" stop-color="rgba(115,103,240,0.6)" offset="1"></stop></linearGradient></defs><g id="SvgjsG1123" class="apexcharts-radialbar"><g id="SvgjsG1124"><g id="SvgjsG1125" class="apexcharts-tracks"><g id="SvgjsG1126" class="apexcharts-radialbar-track apexcharts-track" rel="1"><path id="apexcharts-radialbarTrack-0" d="M 91.53845410946391 259.1233220103534 A 118.9530487804878 118.9530487804878 0 1 1 259.1233220103534 244.46154589053606" fill="none" fill-opacity="1" stroke="rgba(255,255,255,0.85)" stroke-opacity="1" stroke-linecap="butt" stroke-width="22.632926829268296" stroke-dasharray="0" class="apexcharts-radialbar-area" data:pathOrig="M 91.53845410946391 259.1233220103534 A 118.9530487804878 118.9530487804878 0 1 1 259.1233220103534 244.46154589053606"></path></g></g><g id="SvgjsG1132"><g id="SvgjsG1137" class="apexcharts-series apexcharts-radial-series" seriesName="CompletedxTask" rel="1" data:realIndex="0"><path id="SvgjsPath1142" d="M 91.53845410946391 259.1233220103534 A 118.9530487804878 118.9530487804878 0 1 1 286.9530487804878 168" fill="none" fill-opacity="0.85" stroke="url(#SvgjsLinearGradient1138)" stroke-opacity="1" stroke-linecap="butt" stroke-width="22.632926829268296" stroke-dasharray="10" class="apexcharts-radialbar-area apexcharts-radialbar-slice-0" data:angle="230" data:value="85" index="0" j="0" data:pathOrig="M 91.53845410946391 259.1233220103534 A 118.9530487804878 118.9530487804878 0 1 1 286.9530487804878 168"></path></g><circle id="SvgjsCircle1133" r="102.63658536585366" cx="168" cy="168" class="apexcharts-radialbar-hollow" fill="transparent"></circle><g id="SvgjsG1134" class="apexcharts-datalabels-group" transform="translate(0, 0) scale(1)" style="opacity: 1;"><text id="SvgjsText1135" font-family="Public Sans" x="168" y="148" text-anchor="middle" dominant-baseline="auto" font-size="13px" font-weight="600" fill="#a5a3ae" class="apexcharts-text apexcharts-datalabel-label" style="font-family: &quot;Public Sans&quot;;">Completed Task</text><text id="SvgjsText1136" font-family="Public Sans" x="168" y="194" text-anchor="middle" dominant-baseline="auto" font-size="38px" font-weight="600" fill="#5d596c" class="apexcharts-text apexcharts-datalabel-value" style="font-family: &quot;Public Sans&quot;;">85%</text></g></g></g></g><line id="SvgjsLine1143" x1="0" y1="0" x2="336" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1144" x1="0" y1="0" x2="336" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line></g><g id="SvgjsG1120" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend"></div></div></div>
                        <div class="resize-triggers"><div class="expand-trigger"><div style="width: 441px; height: 307px;"></div></div><div class="contract-trigger"></div></div></div>
                </div>
            </div>
        </div>
    </div>
    <!--/ Support Tracker -->

    <!-- Sales By Country -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Sales by Countries</h5>
                    <small class="text-muted">Monthly Sales Overview</small>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="salesByCountry" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="salesByCountry">
                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        <a class="dropdown-item" href="javascript:void(0);">Share</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    <li class="d-flex align-items-center mb-4">
                        <img src="<?= $path ?>/svg/flags/us.svg" alt="User" class="rounded-circle me-3" width="34">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-flex align-items-center">
                                    <h6 class="mb-0 me-1">$8,567k</h6>

                                </div>
                                <small class="text-muted">United states</small>
                            </div>
                            <div class="user-progress">
                                <p class="text-success fw-semibold mb-0">
                                    <i class="ti ti-chevron-up"></i>
                                    25.8%
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-4">
                        <img src="<?= $path ?>/svg/flags/br.svg" alt="User" class="rounded-circle me-3" width="34">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-flex align-items-center">
                                    <h6 class="mb-0 me-1">$2,415k</h6>
                                </div>
                                <small class="text-muted">Brazil</small>
                            </div>
                            <div class="user-progress">
                                <p class="text-danger fw-semibold mb-0">
                                    <i class="ti ti-chevron-down"></i>
                                    6.2%
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-4">
                        <img src="<?= $path ?>/svg/flags/in.svg" alt="User" class="rounded-circle me-3" width="34">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-flex align-items-center">
                                    <h6 class="mb-0 me-1">$865k</h6>
                                </div>
                                <small class="text-muted">India</small>
                            </div>
                            <div class="user-progress">
                                <p class="text-success fw-semibold">
                                    <i class="ti ti-chevron-up"></i>
                                    12.4%
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-4">
                        <img src="<?= $path ?>/svg/flags/au.svg" alt="User" class="rounded-circle me-3" width="34">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-flex align-items-center">
                                    <h6 class="mb-0 me-1">$745k</h6>
                                </div>
                                <small class="text-muted">Australia</small>
                            </div>
                            <div class="user-progress">
                                <p class="text-danger fw-semibold mb-0">
                                    <i class="ti ti-chevron-down"></i>
                                    11.9%
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center mb-4">
                        <img src="<?= $path ?>/svg/flags/fr.svg" alt="User" class="rounded-circle me-3" width="34">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-flex align-items-center">
                                    <h6 class="mb-0 me-1">$45</h6>
                                </div>
                                <small class="text-muted">France</small>
                            </div>
                            <div class="user-progress">
                                <p class="text-success fw-semibold mb-0">
                                    <i class="ti ti-chevron-up"></i>
                                    16.2%
                                </p>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex align-items-center">
                        <img src="<?= $path ?>/svg/flags/cn.svg" alt="User" class="rounded-circle me-3" width="34">
                        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                                <div class="d-flex align-items-center">
                                    <h6 class="mb-0 me-1">$12k</h6>
                                </div>
                                <small class="text-muted">China</small>
                            </div>
                            <div class="user-progress">
                                <p class="text-success fw-semibold mb-0">
                                    <i class="ti ti-chevron-up"></i>
                                    14.8%
                                </p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Sales By Country -->

    <!-- Total Earning -->
    <div class="col-12 col-xl-4 mb-4 col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between pb-1">
                <h5 class="mb-0 card-title">Total Earning</h5>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="totalEarning" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="totalEarning">
                        <a class="dropdown-item" href="javascript:void(0);">View More</a>
                        <a class="dropdown-item" href="javascript:void(0);">Delete</a>
                    </div>
                </div>
            </div>
            <div class="card-body" style="position: relative;">
                <div class="d-flex align-items-center">
                    <h1 class="mb-0 me-2">87%</h1>
                    <i class="ti ti-chevron-up text-success me-1"></i>
                    <p class="text-success mb-0">25.8%</p>
                </div>
                <div id="totalEarningChart" style="min-height: 230px;"><div id="apexchartseaa0fg8i" class="apexcharts-canvas apexchartseaa0fg8i apexcharts-theme-light" style="width: 400px; height: 230px;"><svg id="SvgjsSvg1145" width="400" height="230" xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.dev" class="apexcharts-svg apexcharts-zoomable" xmlns:data="ApexChartsNS" transform="translate(0, 0)" style="background: transparent;"><g id="SvgjsG1147" class="apexcharts-inner apexcharts-graphical" transform="translate(15.89142857142857, -10)"><defs id="SvgjsDefs1146"><linearGradient id="SvgjsLinearGradient1150" x1="0" y1="0" x2="0" y2="1"><stop id="SvgjsStop1151" stop-opacity="0.4" stop-color="rgba(216,227,240,0.4)" offset="0"></stop><stop id="SvgjsStop1152" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop><stop id="SvgjsStop1153" stop-opacity="0.5" stop-color="rgba(190,209,230,0.5)" offset="1"></stop></linearGradient><clipPath id="gridRectMaskeaa0fg8i"><rect id="SvgjsRect1155" width="405.99999999999994" height="245" x="-13.89142857142857" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath><clipPath id="forecastMaskeaa0fg8i"></clipPath><clipPath id="nonForecastMaskeaa0fg8i"></clipPath><clipPath id="gridRectMarkerMaskeaa0fg8i"><rect id="SvgjsRect1156" width="382.21714285714285" height="249" x="-2" y="-2" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fff"></rect></clipPath></defs><rect id="SvgjsRect1154" width="0" height="245" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke-dasharray="3" fill="url(#SvgjsLinearGradient1150)" class="apexcharts-xcrosshairs" y2="245" filter="none" fill-opacity="0.9"></rect><g id="SvgjsG1178" class="apexcharts-xaxis" transform="translate(0, 0)"><g id="SvgjsG1179" class="apexcharts-xaxis-texts-g" transform="translate(0, -4)"></g></g><g id="SvgjsG1188" class="apexcharts-grid"><g id="SvgjsG1189" class="apexcharts-gridlines-horizontal" style="display: none;"><line id="SvgjsLine1191" x1="-11.89142857142857" y1="0" x2="390.1085714285714" y2="0" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1192" x1="-11.89142857142857" y1="49" x2="390.1085714285714" y2="49" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1193" x1="-11.89142857142857" y1="98" x2="390.1085714285714" y2="98" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1194" x1="-11.89142857142857" y1="147" x2="390.1085714285714" y2="147" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1195" x1="-11.89142857142857" y1="196" x2="390.1085714285714" y2="196" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line><line id="SvgjsLine1196" x1="-11.89142857142857" y1="245" x2="390.1085714285714" y2="245" stroke="#e0e0e0" stroke-dasharray="0" stroke-linecap="butt" class="apexcharts-gridline"></line></g><g id="SvgjsG1190" class="apexcharts-gridlines-vertical" style="display: none;"></g><line id="SvgjsLine1198" x1="0" y1="245" x2="378.21714285714285" y2="245" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line><line id="SvgjsLine1197" x1="0" y1="1" x2="0" y2="245" stroke="transparent" stroke-dasharray="0" stroke-linecap="butt"></line></g><g id="SvgjsG1157" class="apexcharts-bar-series apexcharts-plot-series"><g id="SvgjsG1158" class="apexcharts-series" seriesName="Earning" rel="1" data:realIndex="0"><path id="SvgjsPath1160" d="M-4.862791836734694 142L-4.862791836734694 60.124999999999986C-4.862791836734694 56.79166666666666 -3.1961251700680275 55.124999999999986 0.13720816326530638 55.124999999999986L-0.13720816326530638 55.124999999999986C3.196125170068027 55.124999999999986 4.862791836734694 56.79166666666666 4.862791836734694 60.124999999999986L4.862791836734694 60.124999999999986L4.862791836734694 142C4.862791836734694 145.33333333333334 3.1961251700680275 147 -0.13720816326530638 147C-0.13720816326530638 147 0.13720816326530638 147 0.13720816326530638 147C-3.196125170068027 147 -4.862791836734694 145.33333333333334 -4.862791836734694 142C-4.862791836734694 142 -4.862791836734694 142 -4.862791836734694 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M -4.862791836734694 142L -4.862791836734694 60.124999999999986Q -4.862791836734694 55.124999999999986 0.13720816326530638 55.124999999999986L -0.13720816326530638 55.124999999999986Q 4.862791836734694 55.124999999999986 4.862791836734694 60.124999999999986L 4.862791836734694 60.124999999999986L 4.862791836734694 142Q 4.862791836734694 147 -0.13720816326530638 147L 0.13720816326530638 147Q -4.862791836734694 147 -4.862791836734694 142z" pathFrom="M -4.862791836734694 142L -4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L 4.862791836734694 142L -4.862791836734694 142" cy="55.124999999999986" cx="4.862791836734694" j="0" val="15" barHeight="91.87500000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1161" d="M49.168228571428564 142L49.168228571428564 90.75C49.16822857142857 87.41666666666666 50.83489523809523 85.75 54.168228571428564 85.75L53.89381224489795 85.75C57.22714557823129 85.75 58.89381224489796 87.41666666666666 58.89381224489795 90.75L58.89381224489795 90.75L58.89381224489795 142C58.89381224489796 145.33333333333334 57.22714557823129 147 53.89381224489795 147C53.89381224489795 147 54.168228571428564 147 54.168228571428564 147C50.83489523809523 147 49.16822857142857 145.33333333333334 49.168228571428564 142C49.168228571428564 142 49.168228571428564 142 49.168228571428564 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 49.168228571428564 142L 49.168228571428564 90.75Q 49.168228571428564 85.75 54.168228571428564 85.75L 53.89381224489795 85.75Q 58.89381224489795 85.75 58.89381224489795 90.75L 58.89381224489795 90.75L 58.89381224489795 142Q 58.89381224489795 147 53.89381224489795 147L 54.168228571428564 147Q 49.168228571428564 147 49.168228571428564 142z" pathFrom="M 49.168228571428564 142L 49.168228571428564 142L 58.89381224489795 142L 58.89381224489795 142L 58.89381224489795 142L 58.89381224489795 142L 58.89381224489795 142L 49.168228571428564 142" cy="85.75" cx="58.89381224489796" j="1" val="10" barHeight="61.25000000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1162" d="M103.19924897959183 142L103.19924897959183 29.499999999999986C103.19924897959183 26.166666666666657 104.86591564625849 24.499999999999986 108.19924897959183 24.499999999999986L107.92483265306122 24.499999999999986C111.25816598639454 24.499999999999986 112.92483265306122 26.166666666666657 112.92483265306122 29.499999999999986L112.92483265306122 29.499999999999986L112.92483265306122 142C112.92483265306122 145.33333333333334 111.25816598639454 147 107.92483265306122 147C107.92483265306122 147 108.19924897959183 147 108.19924897959183 147C104.86591564625849 147 103.19924897959183 145.33333333333334 103.19924897959183 142C103.19924897959183 142 103.19924897959183 142 103.19924897959183 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 103.19924897959183 142L 103.19924897959183 29.499999999999986Q 103.19924897959183 24.499999999999986 108.19924897959183 24.499999999999986L 107.92483265306122 24.499999999999986Q 112.92483265306122 24.499999999999986 112.92483265306122 29.499999999999986L 112.92483265306122 29.499999999999986L 112.92483265306122 142Q 112.92483265306122 147 107.92483265306122 147L 108.19924897959183 147Q 103.19924897959183 147 103.19924897959183 142z" pathFrom="M 103.19924897959183 142L 103.19924897959183 142L 112.92483265306122 142L 112.92483265306122 142L 112.92483265306122 142L 112.92483265306122 142L 112.92483265306122 142L 103.19924897959183 142" cy="24.499999999999986" cx="112.9248326530612" j="2" val="20" barHeight="122.50000000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1163" d="M157.2302693877551 142L157.2302693877551 103C157.2302693877551 99.66666666666666 158.89693605442176 98 162.2302693877551 98L161.9558530612245 98C165.28918639455785 98 166.9558530612245 99.66666666666666 166.9558530612245 103L166.9558530612245 103L166.9558530612245 142C166.9558530612245 145.33333333333334 165.28918639455785 147 161.9558530612245 147C161.9558530612245 147 162.2302693877551 147 162.2302693877551 147C158.89693605442176 147 157.2302693877551 145.33333333333334 157.2302693877551 142C157.2302693877551 142 157.2302693877551 142 157.2302693877551 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 157.2302693877551 142L 157.2302693877551 103Q 157.2302693877551 98 162.2302693877551 98L 161.9558530612245 98Q 166.9558530612245 98 166.9558530612245 103L 166.9558530612245 103L 166.9558530612245 142Q 166.9558530612245 147 161.9558530612245 147L 162.2302693877551 147Q 157.2302693877551 147 157.2302693877551 142z" pathFrom="M 157.2302693877551 142L 157.2302693877551 142L 166.9558530612245 142L 166.9558530612245 142L 166.9558530612245 142L 166.9558530612245 142L 166.9558530612245 142L 157.2302693877551 142" cy="98" cx="166.95585306122445" j="3" val="8" barHeight="49.00000000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1164" d="M211.26128979591834 142L211.26128979591834 78.5C211.26128979591834 75.16666666666667 212.927956462585 73.5 216.26128979591834 73.5L215.98687346938772 73.5C219.32020680272103 73.5 220.98687346938772 75.16666666666667 220.98687346938772 78.5L220.98687346938772 78.5L220.98687346938772 142C220.98687346938772 145.33333333333334 219.32020680272103 147 215.98687346938772 147C215.98687346938772 147 216.26128979591834 147 216.26128979591834 147C212.927956462585 147 211.26128979591834 145.33333333333334 211.26128979591834 142C211.26128979591834 142 211.26128979591834 142 211.26128979591834 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 211.26128979591834 142L 211.26128979591834 78.5Q 211.26128979591834 73.5 216.26128979591834 73.5L 215.98687346938772 73.5Q 220.98687346938772 73.5 220.98687346938772 78.5L 220.98687346938772 78.5L 220.98687346938772 142Q 220.98687346938772 147 215.98687346938772 147L 216.26128979591834 147Q 211.26128979591834 147 211.26128979591834 142z" pathFrom="M 211.26128979591834 142L 211.26128979591834 142L 220.98687346938772 142L 220.98687346938772 142L 220.98687346938772 142L 220.98687346938772 142L 220.98687346938772 142L 211.26128979591834 142" cy="73.5" cx="220.98687346938772" j="4" val="12" barHeight="73.5" barWidth="9.725583673469387"></path><path id="SvgjsPath1165" d="M265.2923102040816 142L265.2923102040816 41.749999999999986C265.2923102040816 38.41666666666666 266.9589768707483 36.749999999999986 270.2923102040816 36.749999999999986L270.017893877551 36.749999999999986C273.3512272108843 36.749999999999986 275.017893877551 38.41666666666666 275.017893877551 41.749999999999986L275.017893877551 41.749999999999986L275.017893877551 142C275.017893877551 145.33333333333334 273.3512272108843 147 270.017893877551 147C270.017893877551 147 270.2923102040816 147 270.2923102040816 147C266.9589768707483 147 265.2923102040816 145.33333333333334 265.2923102040816 142C265.2923102040816 142 265.2923102040816 142 265.2923102040816 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 265.2923102040816 142L 265.2923102040816 41.749999999999986Q 265.2923102040816 36.749999999999986 270.2923102040816 36.749999999999986L 270.017893877551 36.749999999999986Q 275.017893877551 36.749999999999986 275.017893877551 41.749999999999986L 275.017893877551 41.749999999999986L 275.017893877551 142Q 275.017893877551 147 270.017893877551 147L 270.2923102040816 147Q 265.2923102040816 147 265.2923102040816 142z" pathFrom="M 265.2923102040816 142L 265.2923102040816 142L 275.017893877551 142L 275.017893877551 142L 275.017893877551 142L 275.017893877551 142L 275.017893877551 142L 265.2923102040816 142" cy="36.749999999999986" cx="275.017893877551" j="5" val="18" barHeight="110.25000000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1166" d="M319.3233306122449 142L319.3233306122449 78.5C319.3233306122449 75.16666666666667 320.9899972789116 73.5 324.3233306122449 73.5L324.04891428571426 73.5C327.3822476190476 73.5 329.04891428571426 75.16666666666667 329.04891428571426 78.5L329.04891428571426 78.5L329.04891428571426 142C329.04891428571426 145.33333333333334 327.3822476190476 147 324.04891428571426 147C324.04891428571426 147 324.3233306122449 147 324.3233306122449 147C320.9899972789116 147 319.3233306122449 145.33333333333334 319.3233306122449 142C319.3233306122449 142 319.3233306122449 142 319.3233306122449 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 319.3233306122449 142L 319.3233306122449 78.5Q 319.3233306122449 73.5 324.3233306122449 73.5L 324.04891428571426 73.5Q 329.04891428571426 73.5 329.04891428571426 78.5L 329.04891428571426 78.5L 329.04891428571426 142Q 329.04891428571426 147 324.04891428571426 147L 324.3233306122449 147Q 319.3233306122449 147 319.3233306122449 142z" pathFrom="M 319.3233306122449 142L 319.3233306122449 142L 329.04891428571426 142L 329.04891428571426 142L 329.04891428571426 142L 329.04891428571426 142L 329.04891428571426 142L 319.3233306122449 142" cy="73.5" cx="329.04891428571426" j="6" val="12" barHeight="73.5" barWidth="9.725583673469387"></path><path id="SvgjsPath1167" d="M373.3543510204081 142L373.3543510204081 121.375C373.3543510204081 118.04166666666666 375.0210176870748 116.375 378.3543510204081 116.375L378.0799346938775 116.375C381.41326802721085 116.375 383.0799346938775 118.04166666666666 383.0799346938775 121.375L383.0799346938775 121.375L383.0799346938775 142C383.0799346938775 145.33333333333334 381.41326802721085 147 378.0799346938775 147C378.0799346938775 147 378.3543510204081 147 378.3543510204081 147C375.0210176870748 147 373.3543510204081 145.33333333333334 373.3543510204081 142C373.3543510204081 142 373.3543510204081 142 373.3543510204081 142 " fill="rgba(115,103,240,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="0" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 373.3543510204081 142L 373.3543510204081 121.375Q 373.3543510204081 116.375 378.3543510204081 116.375L 378.0799346938775 116.375Q 383.0799346938775 116.375 383.0799346938775 121.375L 383.0799346938775 121.375L 383.0799346938775 142Q 383.0799346938775 147 378.0799346938775 147L 378.3543510204081 147Q 373.3543510204081 147 373.3543510204081 142z" pathFrom="M 373.3543510204081 142L 373.3543510204081 142L 383.0799346938775 142L 383.0799346938775 142L 383.0799346938775 142L 383.0799346938775 142L 383.0799346938775 142L 373.3543510204081 142" cy="116.375" cx="383.0799346938775" j="7" val="5" barHeight="30.625000000000004" barWidth="9.725583673469387"></path></g><g id="SvgjsG1168" class="apexcharts-series" seriesName="Expense" rel="2" data:realIndex="1"><path id="SvgjsPath1170" d="M-4.862791836734694 157L-4.862791836734694 189.875C-4.862791836734694 193.20833333333331 -3.1961251700680275 194.875 0.13720816326530638 194.875L-0.13720816326530638 194.875C3.196125170068027 194.875 4.862791836734694 193.20833333333331 4.862791836734694 189.875L4.862791836734694 189.875L4.862791836734694 157C4.862791836734694 153.66666666666666 3.1961251700680275 152 -0.13720816326530638 152C-0.13720816326530638 152 0.13720816326530638 152 0.13720816326530638 152C-3.196125170068027 152 -4.862791836734694 153.66666666666666 -4.862791836734694 157C-4.862791836734694 157 -4.862791836734694 157 -4.862791836734694 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M -4.862791836734694 157L -4.862791836734694 189.875Q -4.862791836734694 194.875 0.13720816326530638 194.875L -0.13720816326530638 194.875Q 4.862791836734694 194.875 4.862791836734694 189.875L 4.862791836734694 189.875L 4.862791836734694 157Q 4.862791836734694 152 -0.13720816326530638 152L 0.13720816326530638 152Q -4.862791836734694 152 -4.862791836734694 157z" pathFrom="M -4.862791836734694 157L -4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L 4.862791836734694 157L -4.862791836734694 157" cy="184.875" cx="4.862791836734694" j="0" val="-7" barHeight="-42.875" barWidth="9.725583673469387"></path><path id="SvgjsPath1171" d="M49.168228571428564 157L49.168228571428564 208.25C49.16822857142857 211.58333333333331 50.83489523809523 213.25 54.168228571428564 213.25L53.89381224489795 213.25C57.22714557823129 213.25 58.89381224489796 211.58333333333331 58.89381224489795 208.25L58.89381224489795 208.25L58.89381224489795 157C58.89381224489796 153.66666666666666 57.22714557823129 152 53.89381224489795 152C53.89381224489795 152 54.168228571428564 152 54.168228571428564 152C50.83489523809523 152 49.16822857142857 153.66666666666666 49.168228571428564 157C49.168228571428564 157 49.168228571428564 157 49.168228571428564 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 49.168228571428564 157L 49.168228571428564 208.25Q 49.168228571428564 213.25 54.168228571428564 213.25L 53.89381224489795 213.25Q 58.89381224489795 213.25 58.89381224489795 208.25L 58.89381224489795 208.25L 58.89381224489795 157Q 58.89381224489795 152 53.89381224489795 152L 54.168228571428564 152Q 49.168228571428564 152 49.168228571428564 157z" pathFrom="M 49.168228571428564 157L 49.168228571428564 157L 58.89381224489795 157L 58.89381224489795 157L 58.89381224489795 157L 58.89381224489795 157L 58.89381224489795 157L 49.168228571428564 157" cy="203.25" cx="58.89381224489796" j="1" val="-10" barHeight="-61.25000000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1172" d="M103.19924897959183 157L103.19924897959183 189.875C103.19924897959183 193.20833333333331 104.86591564625849 194.875 108.19924897959183 194.875L107.92483265306122 194.875C111.25816598639454 194.875 112.92483265306122 193.20833333333331 112.92483265306122 189.875L112.92483265306122 189.875L112.92483265306122 157C112.92483265306122 153.66666666666666 111.25816598639454 152 107.92483265306122 152C107.92483265306122 152 108.19924897959183 152 108.19924897959183 152C104.86591564625849 152 103.19924897959183 153.66666666666666 103.19924897959183 157C103.19924897959183 157 103.19924897959183 157 103.19924897959183 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 103.19924897959183 157L 103.19924897959183 189.875Q 103.19924897959183 194.875 108.19924897959183 194.875L 107.92483265306122 194.875Q 112.92483265306122 194.875 112.92483265306122 189.875L 112.92483265306122 189.875L 112.92483265306122 157Q 112.92483265306122 152 107.92483265306122 152L 108.19924897959183 152Q 103.19924897959183 152 103.19924897959183 157z" pathFrom="M 103.19924897959183 157L 103.19924897959183 157L 112.92483265306122 157L 112.92483265306122 157L 112.92483265306122 157L 112.92483265306122 157L 112.92483265306122 157L 103.19924897959183 157" cy="184.875" cx="112.9248326530612" j="2" val="-7" barHeight="-42.875" barWidth="9.725583673469387"></path><path id="SvgjsPath1173" d="M157.2302693877551 157L157.2302693877551 220.5C157.2302693877551 223.83333333333334 158.89693605442176 225.5 162.2302693877551 225.5L161.9558530612245 225.5C165.28918639455785 225.5 166.9558530612245 223.83333333333334 166.9558530612245 220.5L166.9558530612245 220.5L166.9558530612245 157C166.9558530612245 153.66666666666666 165.28918639455785 152 161.9558530612245 152C161.9558530612245 152 162.2302693877551 152 162.2302693877551 152C158.89693605442176 152 157.2302693877551 153.66666666666666 157.2302693877551 157C157.2302693877551 157 157.2302693877551 157 157.2302693877551 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 157.2302693877551 157L 157.2302693877551 220.5Q 157.2302693877551 225.5 162.2302693877551 225.5L 161.9558530612245 225.5Q 166.9558530612245 225.5 166.9558530612245 220.5L 166.9558530612245 220.5L 166.9558530612245 157Q 166.9558530612245 152 161.9558530612245 152L 162.2302693877551 152Q 157.2302693877551 152 157.2302693877551 157z" pathFrom="M 157.2302693877551 157L 157.2302693877551 157L 166.9558530612245 157L 166.9558530612245 157L 166.9558530612245 157L 166.9558530612245 157L 166.9558530612245 157L 157.2302693877551 157" cy="215.5" cx="166.95585306122445" j="3" val="-12" barHeight="-73.5" barWidth="9.725583673469387"></path><path id="SvgjsPath1174" d="M211.26128979591834 157L211.26128979591834 183.75C211.26128979591834 187.08333333333331 212.927956462585 188.75 216.26128979591834 188.75L215.98687346938772 188.75C219.32020680272103 188.75 220.98687346938772 187.08333333333331 220.98687346938772 183.75L220.98687346938772 183.75L220.98687346938772 157C220.98687346938772 153.66666666666666 219.32020680272103 152 215.98687346938772 152C215.98687346938772 152 216.26128979591834 152 216.26128979591834 152C212.927956462585 152 211.26128979591834 153.66666666666666 211.26128979591834 157C211.26128979591834 157 211.26128979591834 157 211.26128979591834 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 211.26128979591834 157L 211.26128979591834 183.75Q 211.26128979591834 188.75 216.26128979591834 188.75L 215.98687346938772 188.75Q 220.98687346938772 188.75 220.98687346938772 183.75L 220.98687346938772 183.75L 220.98687346938772 157Q 220.98687346938772 152 215.98687346938772 152L 216.26128979591834 152Q 211.26128979591834 152 211.26128979591834 157z" pathFrom="M 211.26128979591834 157L 211.26128979591834 157L 220.98687346938772 157L 220.98687346938772 157L 220.98687346938772 157L 220.98687346938772 157L 220.98687346938772 157L 211.26128979591834 157" cy="178.75" cx="220.98687346938772" j="4" val="-6" barHeight="-36.75" barWidth="9.725583673469387"></path><path id="SvgjsPath1175" d="M265.2923102040816 157L265.2923102040816 202.125C265.2923102040816 205.45833333333334 266.9589768707483 207.125 270.2923102040816 207.125L270.017893877551 207.125C273.3512272108843 207.125 275.017893877551 205.45833333333334 275.017893877551 202.125L275.017893877551 202.125L275.017893877551 157C275.017893877551 153.66666666666666 273.3512272108843 152 270.017893877551 152C270.017893877551 152 270.2923102040816 152 270.2923102040816 152C266.9589768707483 152 265.2923102040816 153.66666666666666 265.2923102040816 157C265.2923102040816 157 265.2923102040816 157 265.2923102040816 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 265.2923102040816 157L 265.2923102040816 202.125Q 265.2923102040816 207.125 270.2923102040816 207.125L 270.017893877551 207.125Q 275.017893877551 207.125 275.017893877551 202.125L 275.017893877551 202.125L 275.017893877551 157Q 275.017893877551 152 270.017893877551 152L 270.2923102040816 152Q 265.2923102040816 152 265.2923102040816 157z" pathFrom="M 265.2923102040816 157L 265.2923102040816 157L 275.017893877551 157L 275.017893877551 157L 275.017893877551 157L 275.017893877551 157L 275.017893877551 157L 265.2923102040816 157" cy="197.125" cx="275.017893877551" j="5" val="-9" barHeight="-55.12500000000001" barWidth="9.725583673469387"></path><path id="SvgjsPath1176" d="M319.3233306122449 157L319.3233306122449 177.625C319.3233306122449 180.95833333333334 320.9899972789116 182.625 324.3233306122449 182.625L324.04891428571426 182.625C327.3822476190476 182.625 329.04891428571426 180.95833333333334 329.04891428571426 177.625L329.04891428571426 177.625L329.04891428571426 157C329.04891428571426 153.66666666666666 327.3822476190476 152 324.04891428571426 152C324.04891428571426 152 324.3233306122449 152 324.3233306122449 152C320.9899972789116 152 319.3233306122449 153.66666666666666 319.3233306122449 157C319.3233306122449 157 319.3233306122449 157 319.3233306122449 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 319.3233306122449 157L 319.3233306122449 177.625Q 319.3233306122449 182.625 324.3233306122449 182.625L 324.04891428571426 182.625Q 329.04891428571426 182.625 329.04891428571426 177.625L 329.04891428571426 177.625L 329.04891428571426 157Q 329.04891428571426 152 324.04891428571426 152L 324.3233306122449 152Q 319.3233306122449 152 319.3233306122449 157z" pathFrom="M 319.3233306122449 157L 319.3233306122449 157L 329.04891428571426 157L 329.04891428571426 157L 329.04891428571426 157L 329.04891428571426 157L 329.04891428571426 157L 319.3233306122449 157" cy="172.625" cx="329.04891428571426" j="6" val="-5" barHeight="-30.625000000000004" barWidth="9.725583673469387"></path><path id="SvgjsPath1177" d="M373.3543510204081 157L373.3543510204081 196C373.3543510204081 199.33333333333331 375.0210176870748 201 378.3543510204081 201L378.0799346938775 201C381.41326802721085 201 383.0799346938775 199.33333333333331 383.0799346938775 196L383.0799346938775 196L383.0799346938775 157C383.0799346938775 153.66666666666666 381.41326802721085 152 378.0799346938775 152C378.0799346938775 152 378.3543510204081 152 378.3543510204081 152C375.0210176870748 152 373.3543510204081 153.66666666666666 373.3543510204081 157C373.3543510204081 157 373.3543510204081 157 373.3543510204081 157 " fill="rgba(129,125,141,0.85)" fill-opacity="1" stroke-opacity="1" stroke-linecap="round" stroke-width="0" stroke-dasharray="0" class="apexcharts-bar-area" index="1" clip-path="url(#gridRectMaskeaa0fg8i)" pathTo="M 373.3543510204081 157L 373.3543510204081 196Q 373.3543510204081 201 378.3543510204081 201L 378.0799346938775 201Q 383.0799346938775 201 383.0799346938775 196L 383.0799346938775 196L 383.0799346938775 157Q 383.0799346938775 152 378.0799346938775 152L 378.3543510204081 152Q 373.3543510204081 152 373.3543510204081 157z" pathFrom="M 373.3543510204081 157L 373.3543510204081 157L 383.0799346938775 157L 383.0799346938775 157L 383.0799346938775 157L 383.0799346938775 157L 383.0799346938775 157L 373.3543510204081 157" cy="191" cx="383.0799346938775" j="7" val="-8" barHeight="-49.00000000000001" barWidth="9.725583673469387"></path></g><g id="SvgjsG1159" class="apexcharts-datalabels" data:realIndex="0"></g><g id="SvgjsG1169" class="apexcharts-datalabels" data:realIndex="1"></g></g><line id="SvgjsLine1199" x1="-11.89142857142857" y1="0" x2="390.1085714285714" y2="0" stroke="#b6b6b6" stroke-dasharray="0" stroke-width="1" stroke-linecap="butt" class="apexcharts-ycrosshairs"></line><line id="SvgjsLine1200" x1="-11.89142857142857" y1="0" x2="390.1085714285714" y2="0" stroke-dasharray="0" stroke-width="0" stroke-linecap="butt" class="apexcharts-ycrosshairs-hidden"></line><g id="SvgjsG1201" class="apexcharts-yaxis-annotations"></g><g id="SvgjsG1202" class="apexcharts-xaxis-annotations"></g><g id="SvgjsG1203" class="apexcharts-point-annotations"></g><rect id="SvgjsRect1204" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-zoom-rect"></rect><rect id="SvgjsRect1205" width="0" height="0" x="0" y="0" rx="0" ry="0" opacity="1" stroke-width="0" stroke="none" stroke-dasharray="0" fill="#fefefe" class="apexcharts-selection-rect"></rect></g><g id="SvgjsG1186" class="apexcharts-yaxis" rel="0" transform="translate(-8, 0)"><g id="SvgjsG1187" class="apexcharts-yaxis-texts-g"></g></g><g id="SvgjsG1148" class="apexcharts-annotations"></g></svg><div class="apexcharts-legend" style="max-height: 115px;"></div></div></div>
                <div class="d-flex align-items-start my-4">
                    <div class="badge rounded bg-label-primary p-2 me-3 rounded"><i class="ti ti-currency-dollar ti-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                        <div class="me-2">
                            <h6 class="mb-0">Total Sales</h6>
                            <small class="text-muted">Refund</small>
                        </div>
                        <p class="mb-0 text-success">+$98</p>
                    </div>
                </div>
                <div class="d-flex align-items-start">
                    <div class="badge rounded bg-label-secondary p-2 me-3 rounded"><i class="ti ti-brand-paypal ti-sm"></i></div>
                    <div class="d-flex justify-content-between w-100 gap-2 align-items-center">
                        <div class="me-2">
                            <h6 class="mb-0">Total Revenue</h6>
                            <small class="text-muted">Client Payment</small>
                        </div>
                        <p class="mb-0 text-success">+$126</p>
                    </div>
                </div>
                <div class="resize-triggers"><div class="expand-trigger"><div style="width: 449px; height: 440px;"></div></div><div class="contract-trigger"></div></div></div>
        </div>
    </div>
    <!--/ Total Earning -->

    <!-- Monthly Campaign State -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-0">Monthly Campaign State</h5>
                    <small class="text-muted">8.52k Social Visiters</small>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="MonthlyCampaign" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                        <a class="dropdown-item" href="javascript:void(0);">View All</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0">
                    <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="badge bg-label-success rounded p-2"><i class="ti ti-mail ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                            <h6 class="mb-0 ms-3">Emails</h6>
                            <div class="d-flex">
                                <p class="mb-0 fw-semibold">12,346</p>
                                <p class="ms-3 text-success mb-0">0.3%</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="badge bg-label-info rounded p-2"><i class="ti ti-link ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                            <h6 class="mb-0 ms-3">Opened</h6>
                            <div class="d-flex">
                                <p class="mb-0 fw-semibold">8,734</p>
                                <p class="ms-3 text-success mb-0">2.1%</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="badge bg-label-warning rounded p-2"><i class="ti ti-click ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                            <h6 class="mb-0 ms-3">Clicked</h6>
                            <div class="d-flex">
                                <p class="mb-0 fw-semibold">967</p>
                                <p class="ms-3 text-success mb-0">1.4%</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="badge bg-label-primary rounded p-2"><i class="ti ti-users ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                            <h6 class="mb-0 ms-3">Subscribe</h6>
                            <div class="d-flex">
                                <p class="mb-0 fw-semibold">345</p>
                                <p class="ms-3 text-success mb-0">8.5k</p>
                            </div>
                        </div>
                    </li>
                    <li class="mb-4 pb-1 d-flex justify-content-between align-items-center">
                        <div class="badge bg-label-secondary rounded p-2"><i class="ti ti-alert-triangle ti-sm text-body"></i></div>
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                            <h6 class="mb-0 ms-3">Complaints</h6>
                            <div class="d-flex">
                                <p class="mb-0 fw-semibold">10</p>
                                <p class="ms-3 text-success mb-0">1.5%</p>
                            </div>
                        </div>
                    </li>
                    <li class="d-flex justify-content-between align-items-center">
                        <div class="badge bg-label-danger rounded p-2"><i class="ti ti-ban ti-sm"></i></div>
                        <div class="d-flex justify-content-between w-100 flex-wrap">
                            <h6 class="mb-0 ms-3">Unsubscribe</h6>
                            <div class="d-flex">
                                <p class="mb-0 fw-semibold">86</p>
                                <p class="ms-3 text-success mb-0">0.8%</p>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Monthly Campaign State -->

    <!-- Source Visit -->
    <div class="col-xl-4 col-md-6 order-2 order-lg-1">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <div class="card-title mb-0">
                    <h5 class="mb-0">Source Visits</h5>
                    <small class="text-muted">38.4k Visitors</small>
                </div>
                <div class="dropdown">
                    <button class="btn p-0" type="button" id="sourceVisits" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="sourceVisits">
                        <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
                        <a class="dropdown-item" href="javascript:void(0);">Download</a>
                        <a class="dropdown-item" href="javascript:void(0);">View All</a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 pb-1">
                        <div class="d-flex align-items-start">
                            <div class="badge bg-label-secondary p-2 me-3 rounded"><i class="ti ti-shadow ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Direct Source</h6>
                                    <small class="text-muted">Direct link click</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">1.2k</p>
                                    <div class="ms-3 badge bg-label-success">+4.2%</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3 pb-1">
                        <div class="d-flex align-items-start">
                            <div class="badge bg-label-secondary p-2 me-3 rounded"><i class="ti ti-globe ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Social Network</h6>
                                    <small class="text-muted">Social Channels</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">31.5k</p>
                                    <div class="ms-3 badge bg-label-success">+8.2%</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3 pb-1">
                        <div class="d-flex align-items-start">
                            <div class="badge bg-label-secondary p-2 me-3 rounded"><i class="ti ti-mail ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Email Newsletter</h6>
                                    <small class="text-muted">Mail Campaigns</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">893</p>
                                    <div class="ms-3 badge bg-label-success">+2.4%</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3 pb-1">
                        <div class="d-flex align-items-start">
                            <div class="badge bg-label-secondary p-2 me-3 rounded"><i class="ti ti-external-link ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Referrals</h6>
                                    <small class="text-muted">Impact Radius Visits</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">342</p>
                                    <div class="ms-3 badge bg-label-danger">-0.4%</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mb-3 pb-1">
                        <div class="d-flex align-items-start">
                            <div class="badge bg-label-secondary p-2 me-3 rounded"><i class="ti ti-discount-2 ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">ADVT</h6>
                                    <small class="text-muted">Google ADVT</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">2.15k</p>
                                    <div class="ms-3 badge bg-label-success">+9.1%</div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li class="mb-2">
                        <div class="d-flex align-items-start">
                            <div class="badge bg-label-secondary p-2 me-3 rounded"><i class="ti ti-star ti-sm"></i></div>
                            <div class="d-flex justify-content-between w-100 flex-wrap gap-2">
                                <div class="me-2">
                                    <h6 class="mb-0">Other</h6>
                                    <small class="text-muted">Many Sources</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <p class="mb-0">12.5k</p>
                                    <div class="ms-3 badge bg-label-success">+6.2%</div>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!--/ Source Visit -->
    <!-- Projects table -->
    <div class="col-12 col-xl-8 col-sm-12 order-1 order-lg-2 mb-4 mb-lg-0">
        <div class="card">
            <div class="card-datatable table-responsive">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer"><div class="card-header pb-0 pt-sm-0">
                        <div class="head-label text-center"><h5 class="card-title mb-0">Projects</h5></div>
                        <div class="d-flex justify-content-center justify-content-md-end">
                            <div id="DataTables_Table_0_filter" class="dataTables_filter"><label>Search:<input type="search" class="form-control" placeholder="" aria-controls="DataTables_Table_0"></label>
                            </div></div></div><table class="datatables-projects table border-top dataTable no-footer dtr-column" id="DataTables_Table_0" aria-describedby="DataTables_Table_0_info" style="width: 922px;">
                        <thead>
                            <tr><th class="control sorting_disabled dtr-hidden" rowspan="1" colspan="1" style="width: 0px; display: none;" aria-label=""></th><th class="sorting_disabled dt-checkboxes-cell dt-checkboxes-select-all" rowspan="1" colspan="1" style="width: 17px;" data-col="1" aria-label=""><input type="checkbox" class="form-check-input"></th><th class="sorting sorting_desc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 204px;" aria-label="Name: activate to sort column ascending" aria-sort="descending">Name</th><th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 78px;" aria-label="Leader: activate to sort column ascending">Leader</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 89px;" aria-label="Team">Team</th><th class="w-px-200 sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" style="width: 200px;" aria-label="Status: activate to sort column ascending">Status</th><th class="sorting_disabled" rowspan="1" colspan="1" style="width: 80px;" aria-label="Actions">Actions</th></tr>
                        </thead><tbody><tr class="odd"><td class="  control" tabindex="0" style="display: none;"></td><td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td><td class="sorting_1"><div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar me-2"><span class="avatar-initial rounded-circle bg-label-info">WS</span></div></div><div class="d-flex flex-column"><span class="text-truncate fw-semibold">Website SEO</span><small class="text-truncate text-muted">10 May 2021</small></div></div></td><td>Eileen</td><td><div class="d-flex align-items-center avatar-group"><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/10.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/3.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/2.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/8.png" alt="Avatar" class="rounded-circle pull-up"></div></div></td><td><div class="d-flex align-items-center"><div class="progress w-100 me-3" style="height: 6px;"><div class="progress-bar" style="width: 38%" aria-valuenow="38%" aria-valuemin="0" aria-valuemax="100"></div></div><span>38%</span></div></td><td><div class="d-inline-block"><a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;" class="dropdown-item">Details</a><a href="javascript:;" class="dropdown-item">Archive</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></td></tr><tr class="even"><td class="  control" tabindex="0" style="display: none;"></td><td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td><td class="sorting_1"><div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar me-2"><img src="<?= $path ?>/img/icons/brands/social-label.png" alt="Avatar" class="rounded-circle"></div></div><div class="d-flex flex-column"><span class="text-truncate fw-semibold">Social Banners</span><small class="text-truncate text-muted">03 Jan 2021</small></div></div></td><td>Owen</td><td><div class="d-flex align-items-center avatar-group"><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/11.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/10.png" alt="Avatar" class="rounded-circle pull-up"></div></div></td><td><div class="d-flex align-items-center"><div class="progress w-100 me-3" style="height: 6px;"><div class="progress-bar" style="width: 45%" aria-valuenow="45%" aria-valuemin="0" aria-valuemax="100"></div></div><span>45%</span></div></td><td><div class="d-inline-block"><a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;" class="dropdown-item">Details</a><a href="javascript:;" class="dropdown-item">Archive</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></td></tr><tr class="odd"><td class="  control" tabindex="0" style="display: none;"></td><td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td><td class="sorting_1"><div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar me-2"><img src="<?= $path ?>/img/icons/brands/sketch-label.png" alt="Avatar" class="rounded-circle"></div></div><div class="d-flex flex-column"><span class="text-truncate fw-semibold">Logo Designs</span><small class="text-truncate text-muted">12 Aug 2021</small></div></div></td><td>Keith</td><td><div class="d-flex align-items-center avatar-group"><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/5.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/7.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/12.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/4.png" alt="Avatar" class="rounded-circle pull-up"></div></div></td><td><div class="d-flex align-items-center"><div class="progress w-100 me-3" style="height: 6px;"><div class="progress-bar" style="width: 92%" aria-valuenow="92%" aria-valuemin="0" aria-valuemax="100"></div></div><span>92%</span></div></td><td><div class="d-inline-block"><a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;" class="dropdown-item">Details</a><a href="javascript:;" class="dropdown-item">Archive</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></td></tr><tr class="even"><td class="  control" tabindex="0" style="display: none;"></td><td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td><td class="sorting_1"><div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar me-2"><img src="<?= $path ?>/img/icons/brands/sketch-label.png" alt="Avatar" class="rounded-circle"></div></div><div class="d-flex flex-column"><span class="text-truncate fw-semibold">IOS App Design</span><small class="text-truncate text-muted">19 Apr 2021</small></div></div></td><td>Merline</td><td><div class="d-flex align-items-center avatar-group"><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/2.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/8.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/5.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/1.png" alt="Avatar" class="rounded-circle pull-up"></div></div></td><td><div class="d-flex align-items-center"><div class="progress w-100 me-3" style="height: 6px;"><div class="progress-bar" style="width: 56%" aria-valuenow="56%" aria-valuemin="0" aria-valuemax="100"></div></div><span>56%</span></div></td><td><div class="d-inline-block"><a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;" class="dropdown-item">Details</a><a href="javascript:;" class="dropdown-item">Archive</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></td></tr><tr class="odd"><td class="  control" tabindex="0" style="display: none;"></td><td class="  dt-checkboxes-cell"><input type="checkbox" class="dt-checkboxes form-check-input"></td><td class="sorting_1"><div class="d-flex justify-content-left align-items-center"><div class="avatar-wrapper"><div class="avatar me-2"><img src="<?= $path ?>/img/icons/brands/figma-label.png" alt="Avatar" class="rounded-circle"></div></div><div class="d-flex flex-column"><span class="text-truncate fw-semibold">Figma Dashboards</span><small class="text-truncate text-muted">08 Apr 2021</small></div></div></td><td>Harmonia</td><td><div class="d-flex align-items-center avatar-group"><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/9.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/2.png" alt="Avatar" class="rounded-circle pull-up"></div><div class="avatar avatar-sm"><img src="<?= $path ?>/img/avatars/4.png" alt="Avatar" class="rounded-circle pull-up"></div></div></td><td><div class="d-flex align-items-center"><div class="progress w-100 me-3" style="height: 6px;"><div class="progress-bar" style="width: 25%" aria-valuenow="25%" aria-valuemin="0" aria-valuemax="100"></div></div><span>25%</span></div></td><td><div class="d-inline-block"><a href="javascript:;" class="btn btn-sm btn-icon dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></a><div class="dropdown-menu dropdown-menu-end m-0"><a href="javascript:;" class="dropdown-item">Details</a><a href="javascript:;" class="dropdown-item">Archive</a><div class="dropdown-divider"></div><a href="javascript:;" class="dropdown-item text-danger delete-record">Delete</a></div></div></td></tr></tbody>
                    </table><div class="row mx-2"><div class="col-sm-12 col-md-6"><div class="dataTables_info" id="DataTables_Table_0_info" role="status" aria-live="polite">Showing 1 to 5 of 10 entries</div></div><div class="col-sm-12 col-md-6"><div class="dataTables_paginate paging_simple_numbers" id="DataTables_Table_0_paginate"><ul class="pagination"><li class="paginate_button page-item previous disabled" id="DataTables_Table_0_previous"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="previous" tabindex="0" class="page-link">Previous</a></li><li class="paginate_button page-item active"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="0" tabindex="0" class="page-link">1</a></li><li class="paginate_button page-item "><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="1" tabindex="0" class="page-link">2</a></li><li class="paginate_button page-item next" id="DataTables_Table_0_next"><a href="#" aria-controls="DataTables_Table_0" data-dt-idx="next" tabindex="0" class="page-link">Next</a></li></ul></div></div></div></div>
            </div>
        </div>
    </div>
    <!--/ Projects table -->
</div>