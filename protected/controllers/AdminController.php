<?php

class AdminController extends Controller
{
    public function actionIndex()
    {
        $users = User::model()->findAll();
        $this->render('index', array('users' => $users));
    }

    public function actionUser()
    {
        $model = new UserForm;

        if (isset($_POST['UserForm'])) {
            $model->attributes = $_POST['UserForm'];

            if ($model->validate() && $model->updateUser()) {
                // email address w/ password
                $this->redirect(Yii::app()->createUrl('admin/index'));
            }
        }
        $this->render('user', array('model' => $model));
    }

    public function actionResetPassword()
    {
        $model = new UserForm;
        
        if ($model->resetPassword()) {
            $this->redirect(Yii::app()->createUrl('admin/index'));
        }
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }
}
