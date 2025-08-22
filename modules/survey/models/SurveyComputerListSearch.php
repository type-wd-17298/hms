<?php

namespace app\modules\survey\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class SurveyComputerListSearch extends SurveyComputerList
{
    public $survey_list_problem;
    public $employee_fullname;
    public function rules()
    {
        return [
            [['department_id', 'survey_budget_year', 'survey_list_problem'], 'safe'],
            [['employee_fullname'], 'safe'],
        ];
    }

    public function search($params)
    {
        $query = SurveyComputerList::find()
            ->joinWith(['emp']);

        $this->load($params, '');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 100
            ],
            'sort' => [
                'defaultOrder' => [
                    'department_id' => SORT_ASC,
                    'sub_department_id' => SORT_ASC,
                    'create_at' => SORT_DESC,
                ],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        if (!empty($params['search'])) {
            $query->andFilterWhere([
                'or',
                ['like', 'survey_list_problem', $params['search']],
                ['like', 'employee.employee_fullname', $params['search']]
            ]);
        }

        if (!empty($params['department'])) {
            $query->andWhere(['department_id' => $params['department']]);
        }

        if ($this->survey_budget_year) {
            $query->andWhere(['survey_budget_year' => $this->survey_budget_year]);
        }

        return $dataProvider;
    }
}
