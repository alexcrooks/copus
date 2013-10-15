<?php $this->pageTitle = Yii::app()->name; ?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<table class="table table-med table-striped">
    <thead>
    <tr>
        <td>Date of Observation</td>
        <td>Observer</td>
        <td>Class</td>
        <td>Options</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($observations as $observation): ?>
        <?php $observationData = json_decode($observation->data, true); ?>
        <tr>
            <td><?php echo $observation->date ?></td>
            <td><?php echo $observation->user->first_name ?> <?php echo $observation->user->last_name ?></td>
            <td><?php echo $observationData['class_name'] ?> instructed by <?php echo $observationData['instructor_name'] ?></td>
            <td>
                <i class="icon-print"></i> <a href="<?php echo Yii::app()->createUrl('observation/print', array('id' => $observation->id)) ?>">Print</a>
                <i class="icon-download-alt"></i> <a href="<?php echo Yii::app()->createUrl('observation/excel', array('id' => $observation->id)) ?>">Export</a>
                <i class="icon-signal"></i> <a href="<?php echo Yii::app()->createUrl('observation/graph', array('id' => $observation->id)) ?>">Graph</a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<p><a class="btn btn-primary" href="<?php echo Yii::app()->createUrl('observation/create') ?>">New Session</a></p>