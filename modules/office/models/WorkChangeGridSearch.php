<?php

namespace app\modules\office\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\office\models\WorkChangeGrid;
use app\modules\hr\models\Employee;
use yii\db\Expression;
use app\components\Ccomponent;
use app\models\ExtProfile;
use app\modules\hr\models\ExecutiveHasCdepartment;
use app\modules\hr\models\EmployeePositionHead;
use yii\helpers\ArrayHelper;

class WorkChangeGridSearch extends WorkChangeGrid {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['emp_staff_a', 'emp_staff_b', 'work_change_id', 'work_grid_type_id'], 'integer'],
            [['update_at', 'create_at', 'work_grid_change_date', 'work_grid_change_date_a', 'work_grid_change_date_b'], 'safe'],
            [['work_grid_change_detail'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
// bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {

        $emp = Ccomponent::Emp(Yii::$app->user->identity->profile->cid);
        $profile = @ExtProfile::find()->where(['IN', 'cid', [$emp->employee_cid]])->one();
        $role = Yii::$app->authManager->getRolesByUser(Yii::$app->user->identity->id);
        $sqlWhere = "'" . implode("','", array_keys($role)) . "'";

        $query = WorkChangeGrid::find();
        $query->addSelect('work_change_grid.*,employee_fullname');
        $query->addSelect(['pcheck' => "IF((emp_staff_b = '{$emp->employee_id}' && s.work_status_id = 'L01' && p.process_receiver = '{$emp->employee_id}') || p2.process_receiver = '{$emp->employee_id}'  || work_status_auth IN ({$sqlWhere}) ,1,0)"]);
        $query->orderBy(['pcheck' => SORT_DESC]);
        $query->join('LEFT JOIN', 'work_process_list p', 'p.work_grid_change_id = work_change_grid.work_grid_change_id');
        $query->join('LEFT JOIN', 'work_process_list p2', 'p2.processlist_id = work_change_grid.work_lastprocess_id');
        $query->join('LEFT JOIN', 'work_change_status s', 'work_change_grid.work_status_id = s.work_status_id');
        $query->join('LEFT JOIN', 'employee e', 'e.employee_id = work_change_grid.emp_staff_a');
// add conditions that should always apply here
        $query->groupBy(['work_change_grid.work_grid_change_id']);
        $page = 20;
        if (isset($params['view']) && $params['view'] == 'calendar') {
            if (isset($params['mode']) && $params['mode'] == 'event') {
                $page = 200;
            }
        }


        $cid = \yii::$app->user->identity->profile->cid;
        $emp = Employee::find()
                ->where(['employee_cid' => $cid])
                ->one();
        if (isset($params['view'])) {
            switch ($params['view']) {
                case 'wait':
                    if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('ITAdmin')) {
                        $query->andWhere(['OR',
                            new Expression(" (IF(emp_staff_b = '{$emp->employee_id}' && s.work_status_id = 'L01',1,0)) = 1"),
                            //new Expression(" (IF(leave_main.employee_id = '{$emp->employee_id}' && leave_main.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                            new Expression(" (IF(work_change_grid.work_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                            new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}' && work_change_grid.work_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                        ]);
                    } else {
                        $query->andWhere(['OR',
                            new Expression(" (IF(emp_staff_b = '{$emp->employee_id}' && s.work_status_id = 'L01',1,0)) = 1"),
                            new Expression(" (IF(work_change_grid.emp_staff_a = '{$emp->employee_id}' && work_change_grid.work_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                            new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}' && work_change_grid.work_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                        ]);
                    }
                    break;
                case 'history':
                    if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
                        $query->andWhere(['AND',
                            //['leave_main.employee_id' => $emp->employee_id],
                            new Expression(" IF(work_change_grid.work_status_id = 'L10',1,0) = 1")
                        ]);
                    } else {
                        $userDep = $emp->employee_dep_id;
                        $head = EmployeePositionHead::findOne(['employee_dep_id' => $emp->employee_dep_id, 'employee_id' => $emp->employee_id]);
                        if ($head && $head->executive->employee_executive_level == 4) { //ระดับหัวหน้ากลุ่มงาน
                            $query->andWhere(['AND',
                                ['work_change_grid.employee_dep_id' => $emp->employee_dep_id],
                                new Expression(" IF(work_change_grid.work_status_id = 'L10',1,0) = 1")
                            ]);
                        } else {
                            $query->andWhere(['AND',
                                ['work_change_grid.emp_staff_a' => $emp->employee_id],
                                new Expression(" IF(work_change_grid.work_status_id = 'L10',1,0) = 1")
                            ]);
                        }
                    }
                    break;
                case 'list':

                    break;
                case 'calendar':
                    if (isset($params['mode']) && $params['mode'] == 'event') {
                        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {

                        } else {
                            $depLink = $emp->dep->employee_dep_parent;
                            $depModel = ExecutiveHasCdepartment::find()->where(['employee_dep_id' => $emp->employee_dep_id])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
                            $headModel = EmployeePositionHead::find()->where(['OR', ['employee_dep_id' => $emp->employee_dep_id], ['employee_id' => $emp->employee_id], ['employee_executive_id' => ArrayHelper::getColumn($depModel, 'employee_executive_id')]])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
                            $depModel2 = ExecutiveHasCdepartment::find()->where(['IN', 'employee_executive_id', ArrayHelper::getColumn($headModel, 'employee_executive_id')])->all(); //เพิ่มรายชื่อผู้บริหารที่มีตำแหน่งในการกำกับดูแลในฝ่าย แม้ว่าจะไม่ได้อยู่ในฝ่ายนั้นๆ
                            $depLink = Employee::find()->where(['employee_dep_id' => $depLink])->all(); //เพิ่มรายชื่อหน่วยงานที่เกี่ยวข้อง
                            $query->andWhere(['AND',
                                new Expression(" IF(leave_main.leave_status_id NOT IN ('L00','L08','L09'),1,0) = 1")
                            ]);
                            $query->andWhere(['OR',
                                ['e.employee_dep_id' => $emp->employee_dep_id],
                                ['IN', 'leave_main.employee_id', ArrayHelper::getColumn($headModel, 'employee_id')],
                                ['IN', 'leave_main.employee_dep_id', ArrayHelper::getColumn($headModel, 'employee_dep_id')],
                                ['IN', 'leave_main.employee_dep_id', ArrayHelper::getColumn($depModel2, 'employee_dep_id')],
                                ['IN', 'leave_main.employee_id', ArrayHelper::getColumn($depLink, 'employee_id')],
                            ]);
                        }
                    }


                    break;
                default:

                    break;
            }
        }
        /*
          if (!\Yii::$app->user->can('SuperAdmin') && !\Yii::$app->user->can('HRsAdmin')) {
          $query->andWhere(['OR',
          ['leave_main.employee_id' => $emp->employee_id],
          new Expression(" (IF(leave_assign = '{$emp->employee_id}' && s.leave_status_id = 'L01',1,0)) = 1"),
          //new Expression(" (IF(p.process_receiver = '{$emp->employee_id}' && s.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
          //new Expression(" (IF(p.process_receiver = '{$emp->employee_id}',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
          new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
          ]);
          }
         */
        if (\Yii::$app->user->can('SuperAdmin')) {
//$query->andWhere(['AND', new Expression(" IF(leave_main.leave_status_id NOT IN ('L00') && leave_main.employee_id <> '{$emp->employee_id}' ,1,0) = 1 ")]); //ไม่ให้แสดงเอกสารที่ร่างไว้
        }
        if (\Yii::$app->user->can('HRsAdmin')) {
            $query->andWhere(['OR',
                    //['leave_main.employee_id' => $emp->employee_id],
//new Expression(" (IF(leave_assign = '{$emp->employee_id}' && s.leave_status_id = 'L01',1,0)) = 1"),
//new Expression(" (IF(p.process_receiver = '{$emp->employee_id}',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
//new Expression(" s.leave_status_auth IN ({$sqlWhere}) "),
            ]);
        }

        $search = \Yii::$app->request->get();
        //$yymm = @$search['yy'] . @$search['mm'];
        $dep = @$search['dep'];
        $query->andFilterWhere(['AND', ['work_change_grid.employee_dep_id' => @$dep]]);
        
		$where = '';
        if (isset($search['date_between_a']) && $search['date_between_a'] <> '' && isset($search['date_between_b'])) {
            $startDate = @$search['date_between_a'];
            $endDate = @$search['date_between_b'];
            $where = " work_grid_change_date_a between '{$startDate}' and '{$endDate}'  ";
        }
        // Filter วันที่
        //$query->andFilterWhere(['AND', new Expression($where)]);
        //echo $where;
        $query->andFilterWhere(['AND',
            // ['like', 'leave_main.leave_id', @$params['qsearchManage']],
            ['like', 'employee_fullname', @$params['qsearchManage']],
            new Expression($where),
                //(!empty($yymm)) ?
                //new Expression(" CONCAT(YEAR(work_grid_change_date_a),LPAD(MONTH(work_grid_change_date_a),2,0)) = '{$yymm}' "), //ค้นหาตามเดือน
                //['like', 'leave_address', @$params['qsearchManage']],
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page
            ],
            'sort' => [
                'defaultOrder' =>
                [
                    'create_at' => SORT_DESC
                ]
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        return $dataProvider;
    }

}
