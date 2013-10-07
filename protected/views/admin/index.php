<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Administration';
?>

<h1>Manage Users</h1>

<table class="table table-striped">
    <thead>
    <tr>
        <td>Username (Access Level)</td>
        <td>Name</td>
        <td>Email Address</td>
    </tr>
    </thead>
    <tbody>
<?php foreach ($users as $user): ?>
        <tr>
            <td><a href="<?php echo Yii::app()->createUrl('admin/user', array('id' => $user->id)) ?>"><?php echo $user->username ?></a> (<?php echo ucfirst($user->role) ?>)</td>
            <td><?php echo $user->first_name ?> <?php echo $user->last_name ?></td>
            <td><?php echo $user->email ?></td>
        </tr>
<?php endforeach; ?>
    </tbody>
</table>
<p><a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('admin/user') ?>">Add Users</a></p>