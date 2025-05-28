<?php

namespace app\components;

class CadminManager {
    /*
      public static function getAuthUser($userID = null) {
      $auth = \Yii::$app->authManager->getAssignments($userID);
      $au = '';
      foreach ($auth as $role) {
      $au .= $role->roleName . ',';
      }
      return $au;
      }
     */

    public static function getAuthUser($userID = null) {

        $auth = \Yii::$app->authManager->getRolesByUser($userID);
        $au = '';
        foreach ($auth as $role) {
            $au .= $role->description . ',';
        }
        $au = rtrim($au, ',');
        return $au;
    }

    public static function getAuthUserIC($userID = null) {
        #$auth = \Yii::$app->authManager->getPermissionsByUser($userID);
        $auth = \Yii::$app->authManager->getAssignments($userID);
        $au = '';
        foreach ($auth as $role) {
            if ($role->roleName == 'IC-teams')
                $au .= $role->roleName . ',';
        }

        return $au;
    }

}
