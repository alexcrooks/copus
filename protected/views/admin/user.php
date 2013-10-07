<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Administration';
?>

<?php $form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'login-form',
        'htmlOptions' => array('class' => 'form-signin'),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )
); ?>
<h2 class="form-signin-heading">Update user</h2>
<?php echo $form->textField($model, 'username', array('placeholder' => 'Username')); ?>
<?php echo $form->dropDownList($model, 'role', array('user' => 'User', 'admin' => 'Admin')); ?>
<?php echo $form->textField($model, 'email', array('placeholder' => 'Email address')); ?>
<?php echo $form->textField($model, 'first_name', array('placeholder' => 'First name')); ?>
<?php echo $form->textField($model, 'last_name', array('placeholder' => 'Last name')); ?>
<?php echo $form->error($model, 'username'); ?>
<?php echo $form->error($model, 'email'); ?>
<?php echo $form->error($model, 'first_name'); ?>
<?php echo $form->error($model, 'last_name'); ?>
<?php echo $form->error($model, 'role'); ?>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Update user')
); ?>
<?php $this->endWidget(); ?>