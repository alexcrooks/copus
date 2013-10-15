<?php $this->pageTitle = Yii::app()->name; ?>

<?php $form = $this->beginWidget(
    'bootstrap.widgets.TbActiveForm',
    array(
        'id' => 'graph-form',
        'htmlOptions' => array('class' => 'form-signin'),
        'enableClientValidation' => true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
        ),
    )
); ?>
<h2 class="form-signin-heading">Create Graph</h2>

<?php echo $form->dropDownList($model, 'graphType', array('' => 'Graph Type', 'PieChart' => 'Pie Chart')); ?>
<?php echo $form->dropDownList($model, 'startTime', array_merge(array('' => 'Start Time'), $timeArray)); ?>
<?php echo $form->dropDownList($model, 'endTime', array_merge(array('' => 'End Time'), $timeArray)); ?>
<?php echo $form->error($model, 'graphType'); ?>
<?php echo $form->error($model, 'startTime'); ?>
<?php echo $form->error($model, 'endTime'); ?>
<div></div>
<?php $this->widget(
    'bootstrap.widgets.TbButton',
    array('buttonType' => 'submit', 'type' => 'primary', 'label' => 'Go')
); ?>
<?php $this->endWidget(); ?>