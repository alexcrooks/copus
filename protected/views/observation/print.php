<?php $this->pageTitle = Yii::app()->name; ?>
<div class="observation-print-view">
    <span class="observation-page-header">Classroom Observation Protocol for Undergraduate STEM - COPUS</span>
    <table class="table-observation-info">
        <tr>
            <td>
                <strong>Observer</strong>
                <blockquote>
                    <div class="info-section"><span>Name:</span><?php echo $data['observer_name'] ?></div>
                    <div class="info-section"><span>Location in Class:</span><?php echo $data['observer_location'] ?></div>
                </blockquote>
            </td>
            <td>
                <strong>Class</strong>
                <blockquote>
                    <div class="info-section"><span>Name, Number, Section:</span><?php echo $data['class_name'] ?></div>
                    <div class="info-section"><span>Instructor Name:</span><?php echo $data['instructor_name'] ?></div>
                    <div class="info-section"><span>Instructor Department:</span><?php echo $data['instructor_department'] ?></div>
                </blockquote>
            </td>
        </tr>
    </table>
    <br /><br />
    <table class="table-observation-data" border="1">
        <?php for ($i = 0; $i < (($data['time'] + 2) / 2); $i++): ?>
            <?php if ($i % 10 == 0): ?>
                <tr class="header">
                    <td rowspan="2">0. Min</td>
                    <td colspan="13">1. Students Doing</td>
                    <td colspan="12">2. Instructor Doing</td>
                    <td rowspan="2">3. Eng</td>
                    <td rowspan="2">4. Comments</td>
                </tr>
                <tr class="subheader">
                    <?php foreach ($tableElements as $elementName => $elementDesc): ?>
                        <td><?php echo str_replace(array('student_', 'instructor_'), '', $elementName); ?></td>
                    <?php endforeach; ?>
                </tr>
            <?php endif; ?>
            <tr>
                <td><?php echo ($i * 2) . "-" . (($i * 2) + 2); ?> min</td>

                <?php foreach ($tableElements as $elementName => $elementDesc): ?>
                    <td class="data-taken"><?php echo isset($data['table_' . $elementName][$i]) ? '<img class="data-taken" src="' . $baseUrl . '/images/blackpixel.jpg">' : ''; ?></td>
                <?php endforeach; ?>
                <td><?php echo isset($data['table_Eng'][$i]) ? $data['table_Eng'][$i] : ''; ?></td>

                <td><?php echo isset($data['table_Comments'][$i]) ? $data['table_Comments'][$i] : ''; ?></td>
            </tr>
        <?php endfor; ?>
    </table>
    <table class="table-observation-info">
        <tr>
            <td>
                <strong>Room Information</strong>
                <blockquote>
                    <div class="info-section"><span>Room Layout:</span><?php echo $data['room_layout'] ?></div>
                </blockquote>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Class</strong>
                <blockquote>
                    <div class="info-section"><span>Approximate # Students Present (iClicker):</span><?php echo $data['class_numstudentspresent'] ?></div>
                    <div class="info-section"><span>Unusual Notes About Class:</span><?php echo $data['class_unusual'] ?></div>
                    <div class="info-section"><span>How Varied is the Whole Course?:</span><?php echo $data['class_wholebalance'] ?> Active Students/Instructor Delivery</div>
                    <div class="info-section"><span>How Varied is this Class?:</span><?php echo $data['class_thisbalance'] ?> Active Students/Instructor Delivery</div>
                    <div class="info-section"><span>What Goes on Out of Class?:</span><?php echo implode(", ", $outOfClass) ?></div>
                </blockquote>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Class Narrative (field notes)</strong>
                <blockquote>
                    <?php echo $data['narrative']; ?>
                </blockquote>
            </td>
        </tr>
    </table>
</div>