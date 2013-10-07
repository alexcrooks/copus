<?php
/* @var $this SiteController */

$this->pageTitle = Yii::app()->name . ' - Administration';
?>

<h1>Manage Users</h1>

<?php foreach ($users as $user): ?>
    <table class="table table-striped">
        <thead>
        <tr>
            <td>Username</td>
            <td>Name</td>
            <td>Email Address</td>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td><a href="<?php echo Yii::app()->createUrl('admin/user', array('id' => $user->id)) ?>"><?php echo $user->username ?></a></td>
            <td><?php echo $user->first_name ?> <?php echo $user->last_name ?></td>
            <td><?php echo $user->email ?></td>
        </tr>
        </tbody>
    </table>
<?php endforeach; ?>

