<?php

namespace app\modules\office\components;

use app\modules\hr\models\Employee;
use app\modules\hr\models\EmployeeDep;
use app\modules\office\models\SummaryOperation;
use app\components;

class Ccomponent {

    public static function getListStaff($id) {
        $id = explode(',', $id);
        $models = Employee::find()->where(['IN', 'employee_id', $id])->cache(0)->all();
        $return = [];
        foreach ($models as $model) {
            $return[] = @$model->employee_fullname;
        }
        return $return;
    }

    public static function getListDep($id) {
        $id = explode(',', $id);
        $models = EmployeeDep::find()->where(['IN', 'employee_dep_id', $id])->cache(0)->all();
        $return = [];
        foreach ($models as $model) {
            $return[] = @$model->employee_dep_label;
        }
        return $return;
    }

    public static function OperationUpdate($emp = '') {
        $where = '';
        if ($emp <> '') {
            $where = " AND e.employee_id = '{$emp}' ";
        }

        $queryString = "SELECT
p1.leave_status_id
,e.employee_id
,e.employee_fullname
,COUNT(DISTINCT a.leave_id) AS cc
FROM leave_main a
INNER JOIN leave_process_list p1 ON p1.processlist_id = a.leave_lastprocess_id
INNER JOIN leave_status s ON s.leave_status_id = p1.leave_status_id
INNER JOIN employee e ON  e.employee_id = p1.process_receiver ||
e.employee_id IN (SELECT e2.employee_id
FROM employee e2
INNER JOIN app_profile ap ON BINARY e2.employee_cid = BINARY ap.cid
INNER JOIN app_auth_assignment a3 ON  a3.user_id =  ap.user_id
WHERE a3.item_name = s.leave_status_auth
AND s.leave_status_auth IS NOT NULL
AND s.leave_status_auth IN ('HRsAdmin')
AND e2.employee_status = 1
GROUP BY e2.employee_id
)
WHERE p1.leave_status_id IN ('L01','L02','L03','L04','L05','L99')
{$where}
GROUP BY e.employee_id
";
        $query = \Yii::$app->db->createCommand($queryString)->queryAll();
        foreach ($query as $row) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $row['employee_id']])->one();
            if (!$model2) {
                $model2 = new SummaryOperation();
                $model2->employee_id = $row['employee_id'];
                $model2->cc_leave = $row['cc'];
                $model2->create_at = new \yii\db\Expression('NOW()');
            }
            $model2->cc_leave = $row['cc'];
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }
        if ($emp <> '' && count($query) == 0) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $emp])->one();
            $model2->cc_leave = 0;
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }


        $queryString = "SELECT
e.employee_id
,e.employee_fullname
,COUNT(DISTINCT IF(paperless_direct = 0 ,a.paperless_id,NULL)) AS cc_paperless
,COUNT(DISTINCT IF(paperless_direct > 0  && p1.paperless_status_id NOT IN('F19','F18'),a.paperless_id,NULL)) AS cc_paper
,COUNT(DISTINCT a.paperless_id) AS cc
#,group_concat(distinct s.paperless_status_auth) as gc
FROM paperless a
INNER JOIN paperless_process_list p1 ON p1.processlist_id = a.paperless_lastprocess_id
INNER JOIN paperless_status s ON s.paperless_status_id = p1.paperless_status_id
INNER JOIN employee e ON e.employee_id = p1.process_receiver
/*
||
e.employee_id IN (SELECT e2.employee_id
FROM employee e2
INNER JOIN app_profile ap ON e2.employee_cid = ap.cid
INNER JOIN app_auth_assignment a3 ON  a3.user_id = ap.user_id
WHERE a3.item_name = s.paperless_status_auth
and s.paperless_status_auth is not null
and e2.employee_status = 1
GROUP BY e2.employee_id
)
*/
WHERE a.paperless_status_id NOT IN ('F01','FF')
{$where}
GROUP BY e.employee_id";
        $query = \Yii::$app->db->createCommand($queryString)->queryAll();
        foreach ($query as $row) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $row['employee_id']])->one();
            if (!$model2) {
                $model2 = new SummaryOperation();
                $model2->employee_id = $row['employee_id'];
                $model2->cc_paperless = $row['cc_paperless'];
                $model2->create_at = new \yii\db\Expression('NOW()');
            }
            $model2->cc_paperless = $row['cc_paperless'];
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }

        if ($emp <> '' && count($query) == 0) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $emp])->one();
            $model2->cc_paperless = 0;
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }
		
		
		$budgetYear = \Yii::$app->params['budgetYear'];
        $budgetYear_start = ($budgetYear - 1) . "-10-01";
        $budgetYear_end = ($budgetYear) . "-09-30";

        $queryString = "SELECT
e.employee_id
,e.employee_fullname
,COUNT(DISTINCT IF(vl.paperless_view_id IS NULL,a.paperless_view_id,NULL)) AS cc
,COUNT(DISTINCT IF(vl.paperless_view_id IS NOT NULL,a.paperless_view_id,NULL)) AS cc_keep
FROM employee e
LEFT JOIN paperless_view a ON paperless_view_deps = '86'
 || FIND_IN_SET(e.employee_id, paperless_view_emps)
 || FIND_IN_SET(e.employee_dep_id, paperless_view_deps)
LEFT JOIN paperless_view_list vl ON vl.employee_id = e.employee_id AND a.paperless_view_id = vl.paperless_view_id
WHERE (paperless_view_emps <> '' OR paperless_view_deps <> '')
AND a.employee_id <> e.employee_id
AND e.employee_status = 1
AND a.paperless_view_startdate  >= '{$budgetYear_start}' #AND a.paperless_view_enddate  <= '{$budgetYear_end}'
{$where}
GROUP BY e.employee_id
ORDER BY e.employee_id ASC";
        $query = \Yii::$app->db->createCommand($queryString)->queryAll();
        foreach ($query as $row) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $row['employee_id']])->one();
            if (!$model2) {
                $model2 = new SummaryOperation();
                $model2->employee_id = $row['employee_id'];
                $model2->cc_view = $row['cc'];
                $model2->create_at = new \yii\db\Expression('NOW()');
            }
            $model2->cc_view = $row['cc'];
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }
        if ($emp <> '' && count($query) == 0) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $emp])->one();
            $model2->cc_view = 0;
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }

        $queryString = "SELECT
p1.approval_status_id
,e.employee_id
,e.employee_fullname
,COUNT(*) AS cc
FROM paperless_approval a
INNER JOIN paperless_approval_process_list p1 ON p1.processlist_id = a.approval_lastprocess_id
INNER JOIN paperless_approval_status s ON s.approval_status_id = p1.approval_status_id
INNER JOIN employee e ON e.employee_id = p1.process_receiver || e.employee_id IN (SELECT e2.employee_id
FROM employee e2
INNER JOIN app_profile ap ON BINARY e2.employee_cid = BINARY ap.cid
INNER JOIN app_auth_assignment a3 ON  a3.user_id =  ap.user_id
WHERE  a3.item_name =  s.approval_status_auth
)
WHERE s.approval_status_id IN ('A01','A02','A03','A04','A05','A06')  {$where}

GROUP BY e.employee_id";

        $query = \Yii::$app->db->createCommand($queryString)->queryAll();
        foreach ($query as $row) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $row['employee_id']])->one();
            if (!$model2) {
                $model2 = new SummaryOperation();
                $model2->employee_id = $row['employee_id'];
                $model2->cc_approval = $row['cc'];
                $model2->create_at = new \yii\db\Expression('NOW()');
            }
            $model2->cc_approval = $row['cc'];
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }
        if ($emp <> '' && count($query) == 0) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $emp])->one();
            $model2->cc_approval = 0;
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }

        $queryString = "SELECT
p1.paperless_status_id
,p1.process_receiver
,e.employee_id
,e.employee_fullname
,COUNT(*) AS cc
,MIN(paperless_official_date) AS nn
,MAX(paperless_official_date) AS nn
FROM paperless_official a
LEFT JOIN paperless_process_list p1 ON p1.processlist_id = a.paperless_lastprocess_id
INNER JOIN employee e ON e.employee_id = p1.process_receiver
WHERE a.paperless_official_type = 'BRN'
AND p1.paperless_status_id IN ('F18','F19')
{$where}
GROUP BY p1.paperless_status_id,p1.process_receiver
";
        $query = \Yii::$app->db->createCommand($queryString)->queryAll();
        foreach ($query as $row) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $row['employee_id']])->one();
            if (!$model2) {
                $model2 = new SummaryOperation();
                $model2->employee_id = $row['employee_id'];
                $model2->cc_official = $row['cc'];
                $model2->create_at = new \yii\db\Expression('NOW()');
            }
            $model2->cc_official = $row['cc'];
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }

        if ($emp <> '' && count($query) == 0) {
            $model2 = SummaryOperation::find()->where(['employee_id' => $emp])->one();
            $model2->cc_official = 0;
            $model2->update_at = new \yii\db\Expression('NOW()');
            $model2->save();
        }
    }

    public static function OperationView() {
        \Yii ::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $model = SummaryOperation::find()->where(['employee_id' => $emp->employee_id])->one();
        return $model;
    }

}
