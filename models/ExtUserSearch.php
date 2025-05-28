<?php

/*
 * Modified by silasoft
 *
 */

namespace app\models;

//use dektrium\user\Finder;
use Yii;
//use yii\base\Model;
use yii\data\ActiveDataProvider;
use dektrium\user\models\UserSearch as BaseUserSearch;
use app\modules\covid\components\Cdata;

/**
 * UserSearch represents the model behind the search form about User.
 */
class ExtUserSearch extends BaseUserSearch {

    public $depcode;
    public $cid;

    /** @inheritdoc */
    public function rules() {
        return [
            'fieldsSafe' => [['username', 'email', 'registration_ip', 'created_at', 'depcode'], 'safe'],
            'createdDefault' => ['created_at', 'default', 'value' => null],
        ];
    }

    /** @inheritdoc */
    public function attributeLabels() {
        return [
            'username' => Yii::t('user', 'Username'),
            'email' => Yii::t('user', 'Email'),
            'created_at' => Yii::t('user', 'Registration time'),
            'registration_ip' => Yii::t('user', 'Registration ip'),
            'depcode' => Yii::t('user', 'สถานบริการ'),
            'cid' => Yii::t('user', 'เลขบัตรประชาชน'),
        ];
    }

    public function search($params) {
        $query = $this->finder->getUserQuery();
        $query->joinWith(['profile']);
        //$query->addSelect('*');
        $query->leftJoin(['e' => 'employee'], 'md5(employee_cid) = md5(cid)');
        //$query->orderBy(['employee_dep_id' => SORT_ASC]);
        #อนุญาตให้ admin จังหวัดและอำเภอเข้าจัดการผู้ใช้งานภายในอำเภอตัวเอง
        /*
          if (Yii::$app->user->can('AdminDep')) {
          $gdepcode = \Yii::$app->user->identity->profile->depcode;
          $query->andWhere("depcode = '{$gdepcode}'");
          }
         */
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    // 'employee_dep_id' => SORT_ASC,
                    'created_at' => SORT_DESC,
                ]
            ],
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
        /*
          if (!($this->load($params) && $this->validate())) {
          return $dataProvider;
          }
         */
        if ($this->created_at !== null) {
            $date = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $date, $date + 3600 * 24]);
        }
        /*
          $query->andFilterWhere(['like', 'cid', @$params['search']])
          #->andFilterWhere(['like', 'cid', $this->username])
          ->andFilterWhere(['like', 'name', @$params['search']])
          ->andFilterWhere(['like', 'lname', @$params['search']])
          //->andFilterWhere(['like', 'email', @$params['search']])
          ->andFilterWhere(['like1', 'depcode', @$params['search']]);
         */

        if (@$params['dep']) {
            $query->andFilterWhere(['like', 'depcode', @$params['dep']]);
        }


        $query->andFilterWhere(['OR',
            ['like', 'cid', @$params['search']],
            ['like', "concat(name,' ',lname)", @$params['search']],
            //['like', 'lname', @$params['search']],
            ['like', 'depcode', @$params['search']],
        ]);

        return $dataProvider;
    }

}
