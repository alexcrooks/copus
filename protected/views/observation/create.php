<?php $this->pageTitle = Yii::app()->name; ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/observation.js'); ?>
<span class="observation-page-header">Classroom Observation Protocol for Undergraduate STEM - COPUS</span>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3 sidebar-width">
            <div class="well sidebar-nav-fixed observation-time-sidebar">
                <strong>Official Time</strong>
                <span class="observation-time">00:00:00</span>
                <span class="observation-tick">00:00</span>
                <a class="observation-toggle-tick" href="#">Start Watch</a>
            </div>
        </div>
    </div>
</div>
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Begin the next time interval now</h3>
    </div>
    <div class="modal-body">
        <p>Close to continue.</p>
    </div>
    <div class="modal-footer">
        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
    </div>
</div>
<form method="post">
    <table class="table-observation-info">
        <tr>
            <td>
                <strong>Observer</strong>
                <blockquote>
                    <div><input type="text" name="ObservationForm[observer_name]" placeholder="Name" value="<?php echo $user->first_name . ' ' . $user->last_name ?>"></div>
                    <div><input type="text" name="ObservationForm[observer_location]" placeholder="Location in Class"></div>
                </blockquote>
            </td>
            <td>
                <strong>Class</strong>
                <blockquote>
                    <div><input type="text" name="ObservationForm[class_name]" placeholder="Name, Number, Section"></div>
                    <div><input type="text" name="ObservationForm[instructor_name]" placeholder="Instructor Name"></div>
                    <div><input type="text" name="ObservationForm[instructor_department]" placeholder="Instructor Department"></div>
                </blockquote>
            </td>
        </tr>
    </table>
    <table class="table-observation-data form-horizontal" border="1">
        <tr class="header">
            <td rowspan="2">0. Min</td>
            <td class="right-border" colspan="13">1. Students Doing</td>
            <td colspan="12">2. Instructor Doing</td>
            <td rowspan="2">3. Eng</td>
            <td rowspan="2">4. Comments</td>
        </tr>
        <tr class="subheader">
            <?php foreach ($this->getTableElements() as $elementName => $elementDesc): ?>
                <td<?php echo $elementName == 'student_O' ? ' class="right-border"' : '' ?> title="<?php echo $elementDesc; ?>"><?php echo str_replace(array('student_', 'instructor_', 'DV', 'AD'), array('', '', 'D/V', 'Adm'), $elementName); ?></td>
            <?php endforeach; ?>
        </tr>
        <tr class="row_to_clone">
            <td>0-2 min</td>

            <?php foreach ($this->getTableElements()  as $elementName => $elementDesc): ?>
                <td<?php echo $elementName == 'student_O' ? ' class="right-border"' : '' ?> title="<?php echo $elementDesc; ?>"><input type="checkbox" value="1" name="ObservationForm[table_<?php echo $elementName; ?>][0]" class="checkbox_reg" /></td>
            <?php endforeach; ?>

            <td><select name="ObservationForm[table_Eng][0]" class="input-small"><option value="">?</option><option value="Low">Low</option><option value="Med">Med</option><option value="High">High</option></select></td>

            <td><input type="text" name="ObservationForm[table_Comments][0]"></td>
        </tr>
    </table>
    <table class="table-observation-legend">
        <tr>
            <td><b>1. Students are Doing</b>
                <table>
                    <tr><td>L</td><td>Listening to instructor/taking notes, etc.</td></tr>
                    <tr><td>Ind</td><td>Individual thinking/problem solving. Only mark when an instructor explicitly asks students to think about a clicker question or another question/problem on their own</td></tr>
                    <tr><td>CG</td><td>Discuss clicker question in groups of 2 or more students</td></tr>
                    <tr><td>WG</td><td>Working in groups on worksheet activity</td></tr>
                    <tr><td>OG</td><td>Other assigned group activity, such as responding to instructor question</td></tr>
                    <tr><td>AnQ</td><td>Student answering a question posed by the instructor with rest of class listening</td></tr>
                    <tr><td>SQ</td><td>Student asks question</td></tr>
                    <tr><td>WC</td><td>Engaged in whole class discussion by offering explanations, opinion, judgment, etc. to whole class, often facilitated by instructor.</td></tr>
                    <tr><td>Prd</td><td>Making a prediction about the outcome of demo or experiment</td></tr>
                    <tr><td>SP</td><td>Presentation by student(s)</td></tr>
                    <tr><td>TQ</td><td>Test or quiz</td></tr>
                    <tr><td>W</td><td>Waiting (instructor late, working on fixing AV problems, instructor otherwise occupied, etc.)</td></tr>
                    <tr><td>O</td><td>Other - explain in comments</td></tr>
                </table>
            </td>
            <td><b>2. Instructor is Doing</b>
                <table>
                    <tr><td>Lec</td><td>Lecturing (presenting content, deriving mathematical results, presenting a problem solution)</td></tr>
                    <tr><td>RtW</td><td>Real time writing (board, doc. projector, etc.) (often checked off with Lec)</td></tr>
                    <tr><td>FUp</td><td>Follow-up/feedback on clicker question or activity to entire class</td></tr>
                    <tr><td>PQ</td><td>Posing non-clicker question to students (non-rhetorical)</td></tr>
                    <tr><td>CQ</td><td>Asking a clicker question (mark the entire time the instructor is using a clicker question, not just when first asked)</td></tr>
                    <tr><td>AnQ</td><td>Listening to and answering student questions with entire class listening</td></tr>
                    <tr><td>MG</td><td>Moving through class guiding ongoing student work during active learning task</td></tr>
                    <tr><td>1o1</td><td>One-on-one: extended discussion with  one or a few individuals, not paying attention to the rest of the class (can be along with MG or AnQ)</td></tr>
                    <tr><td>D/V</td><td>Showing or conducting demo, experiment, simulation, video, animation, or photo</td></tr>
                    <tr><td>Adm</td><td>Administration (assign homework, return tests, etc)</td></tr>
                    <tr><td>W</td><td>Waiting - when there is an opportunity for an instructor to be interacting with or observing/listening to students or group activities and the instructor is not doing so.</td></tr>
                    <tr><td>O</td><td>Other - explain in comments</td></tr>
                </table>
            </td>
            <td><b>3. Student Engagement</b>
                <table>
                    <tr><td>L</td><td>Small fraction (10-20%) obviously engaged.</td></tr>
                    <tr><td>M</td><td>Substantial fractions both clearly engaged and clearly not engaged</td></tr>
                    <tr><td>H</td><td>Large fraction of students (80+%) clearly engaged in class activity or listening to instructor.</td></tr>
                </table>
            </td>
        </tr>
    </table>
    <a class="observation-toggle-legend" href="#">Click here to show/hide the legend.</a>
    <table class="table-observation-info">
        <tr>
            <td>
                <strong>Room Information</strong>
                <blockquote>
                    <div><input type="text" name="ObservationForm[room_layout]" placeholder="Room Layout"></div>
                </blockquote>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Class</strong>
                <blockquote>
                    <div><input type="text" name="ObservationForm[class_numstudentspresent]" placeholder="# Students Present (iClicker)"></div>
                    <div><input type="text" name="ObservationForm[class_unusual]" placeholder="Unusual Notes About Class"></div>
                    <div>
                        <select name="ObservationForm[class_wholebalance]">
                            <option value="">How Varied is the Course?</option>
                            <option value="0/100">0% Active Students/100% Instructor Delivery</option>
                            <option value="20/80">20/80</option>
                            <option value="40/60">40/60</option>
                            <option value="60/40">60/40</option>
                            <option value="80/20">80/20</option>
                            <option value="100/0">100/0</option>
                        </select></div>
                    <div>
                        <select name="ObservationForm[class_thisbalance]">
                            <option value="">How Varied is this Class?</option>
                            <option value="0/100">0% Active Students/100% Instructor Delivery</option>
                            <option value="20/80">20/80</option>
                            <option value="40/60">40/60</option>
                            <option value="60/40">60/40</option>
                            <option value="80/20">80/20</option>
                            <option value="100/0">100/0</option>
                        </select></div><br />
                    <strong>What Goes on Out of Class?</strong>
                    <input type="checkbox" value="1" name="ObservationForm[class_ooc_homework]"> Homework
                    <input type="checkbox" value="1" name="ObservationForm[class_ooc_prereading]"> Pre-Readings
                    <input type="checkbox" value="1" name="ObservationForm[class_ooc_labs]"> Labs
                    <input type="checkbox" value="1" name="ObservationForm[class_ooc_projects]"> Projects
                </blockquote>
            </td>
        </tr>
        <tr>
            <td>
                <strong>Class Narrative (field notes)</strong>
                <blockquote>
                    <p>
                        Information could include:<br />
                        &bull; The structure of the lesson (e.g., how the instructor sequenced material, the narrative arc of the class)<br />
                        &bull; The range and nature of activities that occurred.<br />
                        &bull; Dialogue/behaviors that illustrate codes you gave, especially for teaching techniques and student engagement.<br />
                        &bull; Teacher's actions that appear to have affected students' engagement or cognitive demand modes.<br />
                        &bull; Evidence of variability among students (e.g., if small groups, to what extent did groups behave and engage similarly?)
                    </p>
                    <textarea name="ObservationForm[narrative]" class="span6" rows="6"></textarea>
                </blockquote>
            </td>
        </tr>
    </table>
    <input id="maxTime" type="hidden" name="ObservationForm[time]" value="0">
    <input class="btn btn-primary" type="submit" value="Save">
</form>
