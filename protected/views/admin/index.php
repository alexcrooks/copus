<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Administration';
?>

<h2>Manage Users</h2>

<table class="table table-striped">
    <thead>
    <tr>
        <td>Username (Access Level)</td>
        <td>Name</td>
        <td>Email Address</td>
        <td>Actions</td>
    </tr>
    </thead>
    <tbody>
<?php foreach ($users as $user): ?>
        <tr>
            <td><a href="<?php echo Yii::app()->createUrl('admin/user', array('id' => $user->id)) ?>"><?php echo $user->username ?></a> (<?php echo ucfirst($user->role) ?>)</td>
            <td><?php echo $user->first_name ?> <?php echo $user->last_name ?></td>
            <td><?php echo $user->email ?></td>
            <td><a href="<?php echo Yii::app()->createUrl('admin/resetPassword', array('id' => $user->id)) ?>">Reset Password</a></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>
<p><a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('admin/user') ?>">Add Users</a></p>
