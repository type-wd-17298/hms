<?php

namespace app\modules\office\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\office\models\LeaveMain;
use app\modules\hr\models\Employee;
use yii\db\Expression;
use app\components\Ccomponent;
use app\models\ExtProfile;
use app\modules\hr\models\ExecutiveHasCdepartment;
use app\modules\hr\models\EmployeePositionHead;
use yii\helpers\ArrayHelper;

/**
 * LeaveMainSearch represents the model behind the search form of `app\modules\edocument\models\LeaveMain`.
 */
class LeaveMainSearch extends LeaveMain {

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['leave_id', 'leave_type_id', 'employee_id', 'employee_dep_id', 'leave_status_id'], 'integer'],
            [['leave_create', 'leave_start', 'leave_end', 'leave_date_approved', 'leave_date_disapproved', 'leave_detail', 'leave_address', 'leave_file', 'leave_comment'], 'safe'],
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

        $query = LeaveMain::find();
        $query->addSelect('leave_main.*,employee_fullname');
        $query->addSelect(['cc' => "IF(lm2.leave_id IS NULL || lm2.leave_id = '',0,1)", 'pcheck' => "IF((leave_main.leave_assign = '{$emp->employee_id}' && s.leave_status_id = 'L01' && p.process_receiver = '{$emp->employee_id}') || p2.process_receiver = '{$emp->employee_id}'  || leave_status_auth IN ({$sqlWhere}) ,1,0)"]);
        $query->orderBy(['pcheck' => SORT_DESC]);
        $query->join('LEFT JOIN', 'leave_main lm2', 'lm2.leave_cancel_id = leave_main.leave_id');
        $query->join('LEFT JOIN', 'leave_process_list p', 'p.leave_id = leave_main.leave_id');
        $query->join('LEFT JOIN', 'leave_process_list p2', 'p2.processlist_id = leave_main.leave_lastprocess_id');
        $query->join('LEFT JOIN', 'leave_status s', 'leave_main.leave_status_id = s.leave_status_id');
        $query->join('LEFT JOIN', 'employee e', 'e.employee_id = leave_main.employee_id');
        // add conditions that should always apply here
        $query->groupBy(['leave_main.leave_id']);
        $page = 20;

        $sort = [
            'create_at' => SORT_DESC,
        ];

        if (isset($params['view']) && $params['view'] == 'calendar') {
            $sort = [
                //'create_at' => SORT_DESC,
                'employee_id' => SORT_ASC,
            ];
            if (isset($params['mode']) && $params['mode'] == 'event') {
                $page = 5000;
            }
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $page
            ],
            'sort' => [
                'defaultOrder' =>
                $sort
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }
        $cid = \yii::$app->user->identity->profile->cid;
        $emp = Employee::find()
                ->where(['employee_cid' => $cid])
                ->one();
        if (isset($params['view'])) {
            switch ($params['view']) {
                case 'wait':
                    if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
                        $query->andWhere(['OR',
                            new Expression(" (IF(leave_main.leave_assign = '{$emp->employee_id}' && s.leave_status_id = 'L01',1,0)) = 1"),
                            //new Expression(" (IF(leave_main.employee_id = '{$emp->employee_id}' && leave_main.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                            new Expression(" (IF(leave_main.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                            new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}' && leave_main.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                        ]);
                    } else {
                        $query->andWhere(['OR',
                            new Expression(" (IF(leave_main.leave_assign = '{$emp->employee_id}' && s.leave_status_id = 'L01',1,0)) = 1"),
                            new Expression(" (IF(leave_main.employee_id = '{$emp->employee_id}' && leave_main.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                            new Expression(" (IF(p2.process_receiver = '{$emp->employee_id}' && leave_main.leave_status_id <> 'L10',1,0)) = 1"), //แสดงรายการให้ผู้เกี่ยวทราบ/ดำเนินการ
                        ]);
                    }
                    break;
                case 'history':
                    if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
                        $query->andWhere(['AND',
                            //['leave_main.employee_id' => $emp->employee_id],
                            new Expression(" IF(leave_main.leave_status_id = 'L10',1,0) = 1")
                        ]);
                    } else {
                        $query->andWhere(['AND',
                            ['leave_main.employee_id' => $emp->employee_id],
                            new Expression(" IF(leave_main.leave_status_id = 'L10',1,0) = 1")
                        ]);
                    }
                    break;
                case 'list':

                    break;
                case 'calendar':
                    if (isset($params['mode']) && $params['mode'] == 'event') {
                        if (\Yii::$app->user->can('SuperAdmin') || \Yii::$app->user->can('HRsAdmin')) {
//                            $query->andWhere(['AND',
//                                new Expression(" leave_main.employee_dep_id = 36 ")
//                            ]);
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

        $query->andFilterWhere(['OR',
            ['like', 'leave_main.leave_id', @$params['qsearchManage']],
            ['like', 'employee_fullname', @$params['qsearchManage']],
            ['like', 'leave_main.leave_detail', @$params['qsearchManage']],
            ['like', 'leave_main.leave_address', @$params['qsearchManage']],
        ]);

        return $dataProvider;
    }

}
