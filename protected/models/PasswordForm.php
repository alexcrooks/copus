<?php

class PasswordForm extends CFormModel
{
    public $password;
    public $newPassword;
    public $newPasswordConfirm;
    private $_user;

    public function rules()
    {
        return array(
            array('password, newPassword, newPasswordConfirm', 'required'),
            array('password', 'confirmPassword'),
            array(
                'newPassword',
                'compare',
                'compareAttribute' => 'newPasswordConfirm',
                'message' => 'Your passwords do not match'
            ),
        );
    }

    public function confirmPassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->_user = new UserIdentity(Yii::app()->user->name, $this->password);

            if (!$this->_user->authenticate()) {
                $this->addError('password', 'You did not enter your current password.');
            }
        }
    }

    public function changePassword()
    {
        $user = User::model()->findByAttributes(array('username' => Yii::app()->user->name));
        $user->password = crypt($this->newPassword);
        return (bool) $user->save();
    }
}