<?php $this->pageTitle = Yii::app()->name; ?>
<?php Yii::app()->clientScript->registerScriptFile('http://www.google.com/jsapi'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/canvg/canvg.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/canvg/rgbcolor.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/canvg/StackBlur.js'); ?>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});

    function drawVisualization() {
        var dataS = google.visualization.arrayToDataTable([
            ['Task', 'Occurrences'],
            <?php echo $this->countForPieChartDist($this->cleanArray('student', $observationData), $graphSettings['startTime'], $graphSettings['endTime']); ?>
        ]);
        var dataI = google.visualization.arrayToDataTable([
            ['Task', 'Occurrences'],
            <?php echo $this->countForPieChartDist($this->cleanArray('instructor', $observationData), $graphSettings['startTime'], $graphSettings['endTime']); ?>
        ]);
        var dataE = google.visualization.arrayToDataTable([
            ['Task', 'Occurrences'],
            <?php echo $this->countEngForPieChartDist($this->cleanArray('Eng', $observationData), $graphSettings['startTime'], $graphSettings['endTime']); ?>
        ]);
        new google.visualization.<?php echo $graphSettings['graphType']; ?>(document.getElementById('graphStudent')).draw(dataS, null);
        new google.visualization.<?php echo $graphSettings['graphType']; ?>(document.getElementById('graphInstructor')).draw(dataI, null);
        new google.visualization.<?php echo $graphSettings['graphType']; ?>(document.getElementById('graphEngagement')).draw(dataE, null);
    }
    google.setOnLoadCallback(drawVisualization);
</script>
<div>
    <div class="graph">
        <h1>Student</h1> <a id="graphStudentLink" href="#" onclick="convertGraphToImage('#graphStudent', '#graphStudentLink');">Download Image</a>
        <div id="graphStudent"></div>
    </div>
    <div class="graph">
        <h1>Instructor</h1> <a id="graphInstructorLink" href="#" onclick="convertGraphToImage('#graphInstructor', '#graphInstructorLink');">Download Image</a>
        <div id="graphInstructor"></div>
    </div>
    <div class="graph">
        <h1>Engagement</h1> <a id="graphEngagementLink" href="#" onclick="convertGraphToImage('#graphEngagement', '#graphEngagementLink');">Download Image</a>
        <div id="graphEngagement"></div>
    </div>
</div>
<script type="text/javascript">
    function convertGraphToImage(element, output) {
        var svg = $(element).find('svg').parent().html();
        var doc = document;
        var canvas = doc.createElement('canvas');
        canvas.setAttribute('style', 'position: absolute; ' + '');
        doc.body.appendChild(canvas);
        canvg(canvas, svg);
        var imgData = canvas.toDataURL("image/png");
        canvas.parentNode.removeChild(canvas);
        $(output).attr('href', imgData);
        return imgData;
    }
</script>