<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Change Password';
?>

<?php $form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'password-form',
        'htmlOptions' => array('class' => 'form-signin'),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )
); ?>
<h2 class="form-signin-heading">Change Password</h2>
<?php echo $form->passwordField($model, 'password', array('placeholder' => 'Current Password')); ?>
<?php echo $form->passwordField($model, 'newPassword', array('placeholder' => 'New Password')); ?>
<?php echo $form->passwordField($model, 'newPasswordConfirm', array('placeholder' => 'Confirm Password')); ?>
<?php echo $form->error($model, 'password'); ?>
<?php echo $form->error($model, 'newPassword'); ?>
<?php echo $form->error($model, 'newPasswordConfirm'); ?>
<?php $this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Change Password')
); ?>
<?php $this->endWidget(); ?>
