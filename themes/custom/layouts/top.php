<?PHP

use yii\helpers\Url;
use yii\helpers\Html;
?>
<ul class="navbar-nav header-right">
    <li class="nav-item dropdown notification_dropdown">
        <div class="nav-link" >
            <i class="fa-solid fa-file-signature text-dark"></i>
            <span class="badge light text-white bg-danger rounded-circle" id="alertNotification01">-</span>
        </div>
    </li>
    <li class="nav-item dropdown notification_dropdown">
        <div class="nav-link" >
            <i class="fa-solid fa-file-pdf text-dark"></i>
            <span class="badge light text-white bg-danger rounded-circle"  id="alertNotification02">-</span>
        </div>
        <!--        <div class="dropdown-menu dropdown-menu-end">
                    <div id="DZ_W_Notification1" class="widget-media dlab-scroll p-3" style="height:380px;">
                        <ul class="timeline">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="<?= $page->baseUrl ?>/images/avatar/1.jpg">
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Dr sultads Send you Photo</h6>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Resport created successfully</h6>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Reminder : Treatment Time!</h6>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        <img alt="image" width="50" src="<?= $page->baseUrl ?>/images/avatar/1.jpg">
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Dr sultads Send you Photo</h6>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-danger">
                                        KG
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Resport created successfully</h6>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-primary">
                                        <i class="fa fa-home"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Reminder : Treatment Time!</h6>
                                        <small class="d-block">29 July 2020 - 02:26 PM</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <a class="all-notification" href="javascript:void(0);">See all notifications <i class="ti-arrow-end"></i></a>
                </div>-->
    </li>
    <!--    <li class="nav-item dropdown notification_dropdown">
            <a class="nav-link " href="javascript:void(0);" data-bs-toggle="dropdown">
                <svg xmlns="http://www.w3.org/2000/svg" width="23.262" height="24" viewBox="0 0 23.262 24">
                    <g id="icon" transform="translate(-1565 90)">
                        <path id="setting_1_" data-name="setting (1)" d="M30.45,13.908l-1-.822a1.406,1.406,0,0,1,0-2.171l1-.822a1.869,1.869,0,0,0,.432-2.385L28.911,4.293a1.869,1.869,0,0,0-2.282-.818l-1.211.454a1.406,1.406,0,0,1-1.88-1.086l-.213-1.276A1.869,1.869,0,0,0,21.475,0H17.533a1.869,1.869,0,0,0-1.849,1.567L15.47,2.842a1.406,1.406,0,0,1-1.88,1.086l-1.211-.454a1.869,1.869,0,0,0-2.282.818L8.126,7.707a1.869,1.869,0,0,0,.432,2.385l1,.822a1.406,1.406,0,0,1,0,2.171l-1,.822a1.869,1.869,0,0,0-.432,2.385L10.1,19.707a1.869,1.869,0,0,0,2.282.818l1.211-.454a1.406,1.406,0,0,1,1.88,1.086l.213,1.276A1.869,1.869,0,0,0,17.533,24h3.943a1.869,1.869,0,0,0,1.849-1.567l.213-1.276a1.406,1.406,0,0,1,1.88-1.086l1.211.454a1.869,1.869,0,0,0,2.282-.818l1.972-3.415a1.869,1.869,0,0,0-.432-2.385ZM27.287,18.77l-1.211-.454a3.281,3.281,0,0,0-4.388,2.533l-.213,1.276H17.533l-.213-1.276a3.281,3.281,0,0,0-4.388-2.533l-1.211.454L9.75,15.355l1-.822a3.281,3.281,0,0,0,0-5.067l-1-.822L11.721,5.23l1.211.454A3.281,3.281,0,0,0,17.32,3.151l.213-1.276h3.943l.213,1.276a3.281,3.281,0,0,0,4.388,2.533l1.211-.454,1.972,3.414h0l-1,.822a3.281,3.281,0,0,0,0,5.067l1,.822ZM19.5,7.375A4.625,4.625,0,1,0,24.129,12,4.63,4.63,0,0,0,19.5,7.375Zm0,7.375A2.75,2.75,0,1,1,22.254,12,2.753,2.753,0,0,1,19.5,14.75Z" transform="translate(1557.127 -90)"/>
                    </g>
                </svg>

                <span class="badge light text-white bg-primary rounded-circle">-</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <div id="DZ_W_TimeLine02" class="widget-timeline dlab-scroll style-1 ps ps--active-y p-3 height370">
                    <ul class="timeline">
                        <li>
                            <div class="timeline-badge primary"></div>
                            <a class="timeline-panel text-muted" href="javascript:void(0);">
                                <span>10 minutes ago</span>
                                <h6 class="mb-0">Youtube, a video-sharing website, goes live <strong class="text-primary">$500</strong>.</h6>
                            </a>
                        </li>
                        <li>
                            <div class="timeline-badge info">
                            </div>
                            <a class="timeline-panel text-muted" href="javascript:void(0);">
                                <span>20 minutes ago</span>
                                <h6 class="mb-0">New order placed <strong class="text-info">#XF-2356.</strong></h6>
                                <p class="mb-0">Quisque a consequat ante Sit amet magna at volutapt...</p>
                            </a>
                        </li>
                        <li>
                            <div class="timeline-badge danger">
                            </div>
                            <a class="timeline-panel text-muted" href="javascript:void(0);">
                                <span>30 minutes ago</span>
                                <h6 class="mb-0">john just buy your product <strong class="text-warning">Sell $250</strong></h6>
                            </a>
                        </li>
                        <li>
                            <div class="timeline-badge success">
                            </div>
                            <a class="timeline-panel text-muted" href="javascript:void(0);">
                                <span>15 minutes ago</span>
                                <h6 class="mb-0">StumbleUpon is acquired by eBay. </h6>
                            </a>
                        </li>
                        <li>
                            <div class="timeline-badge warning">
                            </div>
                            <a class="timeline-panel text-muted" href="javascript:void(0);">
                                <span>20 minutes ago</span>
                                <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                            </a>
                        </li>
                        <li>
                            <div class="timeline-badge dark">
                            </div>
                            <a class="timeline-panel text-muted" href="javascript:void(0);">
                                <span>20 minutes ago</span>
                                <h6 class="mb-0">Mashable, a news website and blog, goes live.</h6>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </li>-->
    <?PHP if (!Yii::$app->user->isGuest) { ?>
        <li class="nav-item dropdown header-profile">
            <a class="nav-link" href="javascript:void(0);" role="button" data-bs-toggle="dropdown">
                <?= @Html::img($userProfile['pictureUrl'], []); ?>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="<?= Url::to(['/user/settings/profile']) ?>" class="dropdown-item ai-icon">
                    <svg id="icon-user2" xmlns="http://www.w3.org/2000/svg" class="text-primary" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                    <span class="ms-2">Profile </span>
                </a>
                <a href="<?= Url::to(['/user/settings/profile']) ?>" class="dropdown-item ai-icon">
                    <svg id="icon-inbox1" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                    <span class="ms-2">Inbox </span>
                </a>
                <a href="<?= Url::to(['/user/security/logout']) ?>" class="dropdown-item ai-icon" data-method="post">
                    <svg  xmlns="http://www.w3.org/2000/svg" class="text-danger" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                    <span class="ms-2">Logout </span>
                </a>
            </div>
        </li>
    <?PHP } ?>
</ul>