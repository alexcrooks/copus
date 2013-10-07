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
            $user->password = crypt(uniqid());
        }
        $user->username = $this->username;
        $user->email = $this->email;
        $user->first_name = $this->first_name;
        $user->last_name = $this->last_name;
        $user->role = $this->role;
        return (bool) $user->save();
    }
}