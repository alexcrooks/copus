<?php

class ObservationController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl'
        );
    }

    public function accessRules()
    {
        return array(
            array(
                'deny',
                'users' => array('?'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionCreate()
    {
        $this->render('create');
    }

    public function actionPrint($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
        $this->render('print', array('observation' => $observation));
    }

    public function actionExcel($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
    }

    public function actionGraph($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
        $this->render('graph', array('observation' => $observation));
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

    public function actionLogin()
    {
        $model = new LoginForm;

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->render('login', array('model' => $model));
    }

}