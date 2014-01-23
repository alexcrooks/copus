<?php

class UserForm extends CFormModel
{
    public $id;
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $role;

    private $_user;

    public function init()
    {
        if (isset($_GET['id'])) {
            $this->_user = User::model()->findByAttributes(array('id' => $_GET['id']));
            $this->id = $this->_user->id;
            $this->username = $this->_user->username;
            $this->email = $this->_user->email;
            $this->first_name = $this->_user->first_name;
            $this->last_name = $this->_user->last_name;
            $this->role = $this->_user->role;
        } else {
            $this->role = 'user';
        }
    }

    public function rules()
    {
        return array(
            array('username, email, first_name, last_name, role', 'required'),
            array('email', 'email'),
            array('username, email', 'length', 'max' => 128),
            array('first_name, last_name', 'safe'),
        );
    }

    public function updateUser()
    {
        if ($this->id) {
            $user = User::model()->findByAttributes(array('id' => $this->id));
        } else {
            $user = new User;
            $dirtyPassword = uniqid();
            $user->password = crypt($dirtyPassword);
        }
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->role = $this->role;

        if (YII_DEBUG == true || $this->id)
            return $user->save();
        else
            return $user->save() && $this->emailUser($user, $dirtyPassword);
    }

    public function resetPassword()
    {
        $user = User::model()->findByAttributes(array('id' => $this->id));
        $dirtyPassword = uniqid();
        $user->password = crypt($dirtyPassword);
        return $user->save() && $this->emailUser($user, $dirtyPassword);
    }

    private function emailUser($user, $dirtyPassword)
    {
        $to = $user->email;
        $subject = "Account Created on COPUS";
        $message = "Hello " . $user->first_name . "\n\n"
            . "An account has been created for you on the COPUS app. You may access it at the following URL: " . Yii::app()->createAbsoluteUrl('site/index') . "\n\n"
            . "Your username is: " . $user->username . "\n"
            . "Your password is: " . $dirtyPassword . "\n\n"
            . "You may change your password once you log in by going to the 'Settings' page.";

        if (Yii::app()->params['useSmtp'] === '<SMTP_USER>') {
            return mail($to, $subject, $message);
        } else {
            $mail = Yii::app()->Smtpmail;
            $mail->SetFrom(Yii::app()->params['useSmtp'], '');
            $mail->Subject = $subject;
            $mail->MsgHTML(nl2br($message));
            $mail->AddAddress($to, '');
            return $mail->Send();
        }
    }
}
