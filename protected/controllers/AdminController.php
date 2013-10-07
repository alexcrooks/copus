<?php

class AdminController extends Controller
{
    public function actionIndex()
    {
        $users = User::model()->findAll();
        $this->render('index', array('users' => $users));
    }

    public function actionUser($id)
    {
        $model = new UserForm;

        if (isset($_POST['UserForm'])) {
            $model->attributes = $_POST['UserForm'];

            if ($model->validate() && $model->updateUser()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $user = User::model()->findByAttributes(array('id' => $id));
        $this->render('user', array('user' => $user, 'model' => $model));
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