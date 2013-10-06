<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form TbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Login';
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
<h2 class="form-signin-heading">Please sign in</h2>
<?php echo $form->textField($model, 'username', array('placeholder' => 'Username')); ?>
<?php echo $form->passwordField($model, 'password', array('placeholder' => 'Password')); ?>
<?php echo $form->error($model, 'username'); ?>
<?php echo $form->error($model, 'password'); ?>
<label class="checkbox">
    <?php echo $form->checkBox($model, 'rememberMe'); ?> Remember me
    <?php echo $form->error($model, 'rememberMe'); ?>
</label>

<?php $this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Sign in')
); ?>
<?php $this->endWidget(); ?>
