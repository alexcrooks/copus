<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="language" content="en">
    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
    <?php
    Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/main.css');
    ?>
</head>
<body>
<div class="navbar navbar-inverse navbar-fixed-top">
    <div class="navbar-inner">
        <div class="container">
            <button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="brand" href="#"><?php echo CHtml::encode(Yii::app()->name); ?></a>

            <div class="nav-collapse collapse">
                <?php $this->widget(
                    'bootstrap.widgets.TbMenu',
                    array(
                        'items' => array(
                            array('label' => 'Home', 'url' => array('/site/index')),
                            array(
                                'label' => 'New Session',
                                'url' => array('/observation/create'),
                                'visible' => !Yii::app()->user->isGuest
                            ),
                            array(
                                'label' => 'Administration',
                                'url' => array('/admin/index'),
                                'visible' => !Yii::app()->user->isGuest
                            ),
                            array(
                                'label' => 'Settings',
                                'url' => array('/site/password'),
                                'visible' => !Yii::app()->user->isGuest
                            ),
                            array(
                                'label' => 'Login',
                                'url' => array('/site/login'),
                                'visible' => Yii::app()->user->isGuest
                            ),
                            array(
                                'label' => 'Logout (' . Yii::app()->user->name . ')',
                                'url' => array('/site/logout'),
                                'visible' => !Yii::app()->user->isGuest
                            )
                        ),
                    )
                ); ?>
            </div>
        </div>
    </div>
</div>
<?php echo $content; ?>
</body>
</html>