<?php

class WebUser extends CWebUser
{
    public function isAdmin()
    {
        $user = User::model()->findByAttributes(array('id' => Yii::app()->user->id));
        return $user->role == 'admin';
    }
}
