<?PHP

use yii\helpers\Url;
?>
<ul class="metismenu" id="menu">
    <li class="mm-active"><a class="has-arrow " href="<?= Url::to(['/plan/default/']) ?>" aria-expanded="true">
            <i class="flaticon-025-dashboard"></i>
            <span class="nav-text">ดำเนินการปี 2568</span>
        </a>
        <ul aria-expanded="false">
		            <?PHP if (\Yii::$app->user->can('SuperAdmin') || 1 ) { ?>
		    <li>
                <a href="<?= Url::to(['/office/license/index']) ?>">ข้อมูลทะเบียนรถยนต์เจ้าหน้าที่ รพ.
                    <span class="badge badge-xs badge-danger blink">มาใหม่</span>
                </a>
            </li>
			<?PHP } ?>
            <li>
                <a href="<?= Url::to(['/plan/default/']) ?>">แผนปฏิบัติการ 2568</a>
            </li>
            <li>
                <a href="<?= Url::to(['/survey/default/']) ?>">สำรวจความต้องการครุภัณฑ์คอมพิวเตอร์ 2568</a>
            </li> 
        </ul>
    </li>
    <li class="<?= (Yii::$app->homeUrl == Yii::$app->request->url ? 'mm-active' : '') ?>">
        <a class="has-arrow" href="javascript:void()" aria-expanded="true">
            <i class="flaticon-041-graph"></i>
            <span class="nav-text">หนังสืออิเลกทรอนิกส์</span>
        </a>
        <ul aria-expanded="false">
<!--            <li><a href="<?= Url::to(['/office/default/']) ?>">Dashboard</a></li>-->
            <li><a href="<?= Url::to(['/office/official/executive']) ?>">เอกสารรอดำเนินการ</a></li>
            <?PHP if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('ManagerAdmin') || \Yii::$app->user->can('ExecutiveUser') || \Yii::$app->user->can('SecretaryAdmin')) { ?>
                <li>
                    <a href="<?= Url::to(['/office/official/index']) ?>">แฟ้มหนังสือราชการ
                        <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_official">-</span>
                    </a>
                </li>
            <?PHP } ?>
            <li>
                <a href="<?= Url::to(['/office/paperless/index']) ?>">แฟ้มบันทึกข้อความ
                    <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_paperless">-</span>
                </a>
            </li>
            <li><a href="<?= Url::to(['/office/view/index']) ?>">แฟ้มหนังสือเวียน
                    <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_view">-</span>
                </a></li>
            <li><a href="<?= Url::to(['/office/approval/index']) ?>">แฟ้มไปราชการ
                    <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_approval">-</span>
                </a></li>
            <li><a href="<?= Url::to(['/office/approval/index2']) ?>">แฟ้มใช้รถส่วนกลาง
                    <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_approval">-</span>
                </a></li>
            <li><a href="<?= Url::to(['/office/dead/index']) ?>">แฟ้มหนังสือรับรองการตาย
                    <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_approval">-</span>
                </a></li>
            <li><a href="<?= Url::to(['/office/leavemain/index']) ?>">แฟ้มการลา
                    <span class="badge badge-xs text-white bg-danger badge-circle d-none float-right" id="cc_leave">-</span>
                </a></li>

            <li><a href="<?= Url::to(['/office/work/index']) ?>">แฟ้มตารางเวร</a></li>

            <li><a href="<?= Url::to(['/office/slip/index']) ?>">สลิปเงินเดือน</a></li>
            <li><a href="<?= Url::to(['/office/book/index']) ?>">จองห้องประชุม</a></li>
            <?PHP if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('OfficeAdmin') || \Yii::$app->user->can('ManagerAdmin') || \Yii::$app->user->can('ExecutiveUser') || \Yii::$app->user->can('SecretaryAdmin')) { ?>
                <li><a href="<?= Url::to(['/office/default/myoffice']) ?>">รายงานทะเบียนเอกสาร</a></li>
            <?PHP } ?>
        </ul>
    </li>
    <?PHP if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')) { ?>
        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-025-dashboard"></i>
                <span class="nav-text">Service Desk (ITSD)</span>
            </a>
            <ul aria-expanded="false">
                <li>
                    <a href="<?= Url::to(['/servicedesk/default/index']) ?>">Dashboard</a>
                </li>
                <li>
                    <a href="<?= Url::to(['/servicedesk/manage/ticket/']) ?>">แจ้งปัญหา
                        <span class="badge badge-xs  badge-danger"><i class="fas fa-phone-alt fa-xs"></i> NEW</span>
                    </a>
                </li>
                <li><a href="<?= Url::to(['/servicedesk/manage/index']) ?>">IT Contact Center</a></li>
                <li><a href="<?= Url::to(['/servicedesk/default/']) ?>">SLA Dashboard</a></li>
                <li><a href="<?= Url::to(['/servicedesk/default/report']) ?>">SLA Report</a></li>
                <li><a href="<?= Url::to(['/servicedesk/default/incidence']) ?>">Incidence Report</a></li>
				 <li><a href="<?= Url::to(['/servicedesk/activity-report/index']) ?>">Activity Report 8 ชม/วัน</a></li>
                <li><a href="<?= Url::to(['/servicedesk/aitss/']) ?>">บันทึกภาระงาน IT</a></li>
                <li>
                    <a href="<?= Url::to(['/servicedesk/software/index']) ?>">ทะเบียนซอฟต์แวร์</a>
                </li>
                <li><a href="<?= Url::to(['/servicedesk/asset/index']) ?>">ทะเบียนคอมพิวเตอร์</a></li>
            </ul>

        </li>

        <!--
                <li>
                    <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-windows"></i>
                        <span class="nav-text">ศูนย์เทคโนโยสารสนเทศ</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="<?= Url::to(['/nurse/work/report']) ?>"></a></li>
                        <li><a href="<?= Url::to(['/nurse/work/report']) ?>">แลกเวร/โอนเวร/แลกเวรวันหยุด</a></li>
                    </ul>
                </li>
                <li>
                    <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-windows"></i>
                        <span class="nav-text">ระบบจัดการเวร</span>
                    </a>
                    <ul aria-expanded="false">
                        <li><a href="<?= Url::to(['/nurse/work/report']) ?>">จัดการตารางเวร</a></li>
                        <li><a href="<?= Url::to(['/nurse/work/report']) ?>">แลกเวร/โอนเวร/แลกเวรวันหยุด</a></li>
                    </ul>
                </li>

        <li>
            <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                <i class="flaticon-381-windows"></i>
                <span class="nav-text">ภาระงานการพยาบาล</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/nurse/work/report']) ?>">รายงานภาระงาน</a></li>
                <li><a href="<?= Url::to(['/nurse/work/create']) ?>">บันทึกภาระงาน</a></li>

            </ul>
        </li>
        -->
        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-085-signal"></i>
                <span class="nav-text">ระบบแผนโครงการ</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/hr/employee/']) ?>">จัดการข้อมูลบุคลากร</a></li>
            </ul>
        </li>
    <?PHP } ?>
    <?PHP if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('StockStaff')) { ?>
        <li>
            <a class="has-arrow" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-043-menu"></i>
                <span class="nav-text">ระบบบริหารคลังพัสดุ</span>
    <!--                <span class="badge badge-xs style-1 badge-danger">New</span>-->
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/inventory/stock-in/']) ?>">ลงรับสินค้าเข้าคลัง</a></li>
                <li><a href="<?= Url::to(['/inventory/stock-out/']) ?>">เบิก/จ่ายวัสดุ</a></li>
                <li><a href="<?= Url::to(['/inventory/items/']) ?>">รายการวัสดุ/ครุภัณฑ์</a></li>
                <li><a href="<?= Url::to(['/inventory/default/']) ?>">เช็คสต็อกรวม</a></li>
                <li><a href="<?= Url::to(['/inventory/categories/']) ?>">หมวดหมู่รายการ</a></li>
                <li><a href="<?= Url::to(['/inventory/default/summary']) ?>">สรุปยอดวัสดุคงเหลือรวม</a></li>
                <!--
                            <li><a href="<?= Url::to(['/inventory/0/']) ?>">จัดการข้อมูลพื้นฐาน</a></li>

                            <li><a href="<?= Url::to(['/inventory/0/']) ?>">สรุปยอดวัสดุคงเหลือแยกหมวด</a></li>
                            <li><a href="<?= Url::to(['/inventory/0/']) ?>">บัญชีวัสดุคงเหลือ</a></li>
                            <li><a href="<?= Url::to(['/inventory/user/']) ?>">ผู้ใช้งาน</a></li>
                -->
            </ul>
        </li>
    <?PHP } ?>

    <?PHP if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) { ?>
        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-085-signal"></i>
                <span class="nav-text">ระบบงานบุคคล</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/hr/employee/']) ?>">จัดการข้อมูลบุคลากร</a></li>
    <!--                <li><a href="<?= Url::to(['/hr/position/']) ?>">จัดการข้อมูลตำแหน่ง</a></li>-->
                <li><a href="<?= Url::to(['/hr/executive/']) ?>">จัดการข้อมูลตำแหน่งบริหาร</a></li>
                <li><a href="<?= Url::to(['/hr/department/']) ?>">จัดการข้อมูลหน่วยงาน</a></li>
                <li><a href="<?= Url::to(['/hr/structure/']) ?>">จัดการโครงสร้างหน่วยงาน</a></li>
                <li><a href="<?= Url::to(['/hr/employee/report']) ?>">รายงาน</a></li>
            </ul>
        </li>

    <?PHP } ?>
    <?PHP if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('AssetAdmin')) { ?>
        <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">
                <i class="flaticon-061-outside"></i>
                <span class="nav-text">ระบบบริหารงานพัสดุ</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/project/default/']) ?>">ทะเบียนจัดซื้อจัดจ้าง</a></li>
                <li><a href="<?= Url::to(['/project/default/po']) ?>">ทะเบียนเลขโครงการระบบ e-GP</a></li>
                <li><a href="<?= Url::to(['/project/default/contract']) ?>">ทะเบียนเลขสัญญาจัดซื้อจัดจ้าง</a></li>
                <li><a href="<?= Url::to(['/project/type/']) ?>">จัดการข้อมูลประเภท</a></li>
                <li><a href="<?= Url::to(['/project/report/summary']) ?>">แบบสรุปผลการดำเนินการจัดซื้อ</a></li>
            </ul>
        </li>
    <?PHP } ?>

    <?PHP if (\Yii::$app->user->can('SuperAdmin') && 0) { ?>
        <li>
            <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                <i class="flaticon-018-clock"></i>
                <span class="nav-text">ระบบจัดการเวร</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/user/admin']) ?>">จัดตารางเวร</a></li>
                <li><a href="<?= Url::to(['/admin']) ?>">แลกเวร</a></li>
                <li><a href="<?= Url::to(['/admin']) ?>">รายงานการจัดเวร</a></li>
            </ul>
        </li>

    <?PHP } ?>
    <?PHP if (\Yii::$app->user->can('SuperAdmin') && 0) { ?>
        <li>
            <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                <i class="flaticon-018-clock"></i>
                <span class="nav-text">ระบบบริหารคลัง</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/user/admin123']) ?>">การจัดการ</a></li>
            </ul>
        </li>
    <?PHP } ?>
    <?PHP if (\Yii::$app->user->can('SuperAdmin')) { ?>
        <li>
            <a class="has-arrow " href="javascript:void()" aria-expanded="false">
                <i class="flaticon-022-copy"></i>
                <span class="nav-text">จัดการระบบ</span>
            </a>
            <ul aria-expanded="false">
                <li><a href="<?= Url::to(['/user/admin']) ?>">จัดการผู้ใช้งาน</a></li>
                <li><a href="<?= Url::to(['/admin']) ?>">จัดการสิทธิผู้ใช้งาน</a></li>
            </ul>
        </li>
    <?PHP } ?>
    <li class="nav-item">
        <a class="nav-link"  aria-expanded="false" href="<?= Url::to(\app\modules\line\components\lineBot::linelink()) ?>">
            <i class="flaticon-012-checkmark"></i>
            <span class="nav-text font-weight-bold1">รับแจ้งเตือนผ่าน Line</span>
        </a>
    </li>
</ul>
