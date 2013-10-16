<?php

class ObservationController extends Controller
{
    public function filters()
    {
        return array(
            'accessControl'
        );
    }

    public function accessRules()
    {
        return array(
            array(
                'deny',
                'users' => array('?'),
            ),
        );
    }

    public function actions()
    {
        return array(
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function actionCreate()
    {
        if (isset($_POST['ObservationForm'])) {
            $formData = $this->objectToArray($_POST['ObservationForm']);
            $formData = $this->arrayToCleanJSON($formData);
            $model = new Observation;
            $model->user_id = Yii::app()->user->id;
            $model->data = $formData;
            if ($model->save())
                $this->redirect(Yii::app()->createUrl('site/index'));
        }
        $this->render('create');
    }

    public function actionPrint($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
        $data = json_decode($observation->data, true);
        $tableElements = self::getTableElements();

        $outOfClass = array();
        $outOfClass[] = isset($data['class_ooc_homework']) ? 'Homework' : '';
        $outOfClass[] = isset($data['class_ooc_prereading']) ? 'Pre-Readings' : '';
        $outOfClass[] = isset($data['class_ooc_labs']) ? 'Labs' : '';
        $outOfClass[] = isset($data['class_ooc_projects']) ? 'Projects' : '';
        $outOfClass = array_filter($outOfClass);
        $this->render('print', array('data' => $data, 'tableElements' => $tableElements, 'outOfClass' => $outOfClass));
    }

    public function actionExcel($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
        $data = json_decode($observation->data, true);
        Yii::import('ext.phpexcel.XPHPExcel');
        $excel = XPHPExcel::createPHPExcel();
        $excel->getProperties()->setTitle('CDOP Report for ' . $data['instructor_name'] . ' on ' . $observation->date);
        $excel->setActiveSheetIndex(0);

        $activeSheet = $excel->getActiveSheet();
        $activeSheet->getSheetView()->setZoomScale(70);
        $excel->getDefaultStyle()->getFont()->setName('Calibri')
            ->setSize(11);
        $activeSheet->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4)
            ->setRowsToRepeatAtTopByStartAndEnd(1, 2)
            ->setVerticalCentered(false)
            ->setHorizontalCentered(true);
        $activeSheet->getDefaultColumnDimension()->setWidth(5);
        $excel->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $activeSheet->getPageMargins()->setTop(0.25)
            ->setRight(0.25)
            ->setBottom(0.25)
            ->setLeft(0.25);
        $activeSheet->setShowGridlines(true);

        $activeSheet->setCellValue(
            'A1',
            'Observed by: ' . $data['observer_name'] . '; Seated at: ' . $data['observer_location']
        );
        $activeSheet->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->mergeCells('A1:Z1');

        $activeSheet->setCellValue(
            'A2',
            $data['class_name'] . ' instructed by ' . $data['instructor_name'] . ' (' . $data['instructor_department'] . ') on ' . $observation->date
        );
        $activeSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->mergeCells('A2:Z2');

        $activeSheet->setCellValue(
            'A3',
            $data['class_numstudentspresent'] . ' students present; Class arrangement: ' . $data['room_layout']
        );
        $activeSheet->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
        $activeSheet->mergeCells('A3:Z3');

        $rowNum = 7;

        for ($i = 0; $i < (($data['time'] + 2) / 2); $i++) {
            if ($i % 10 == 0) {
                $activeSheet->setCellValue('A' . $rowNum, 'min');
                $activeSheet->getStyle('A' . $rowNum)->getFont()->setBold(true);
                $activeSheet->mergeCells('A' . $rowNum . ':A' . ($rowNum + 1));

                $activeSheet->setCellValue('B' . $rowNum, '1. Students Doing');
                $activeSheet->getStyle('B' . $rowNum)->getFont()->setBold(true);
                $activeSheet->mergeCells('B' . $rowNum . ':N' . $rowNum);

                $activeSheet->setCellValue('O' . $rowNum, '2. Instructor Doing');
                $activeSheet->getStyle('O' . $rowNum)->getFont()->setBold(true);
                $activeSheet->mergeCells('O' . $rowNum . ':Z' . $rowNum);

                $activeSheet->setCellValue('AA' . $rowNum, '3. Eng');
                $activeSheet->getStyle('AA' . $rowNum)->getFont()->setBold(true);
                $activeSheet->mergeCells('AA' . $rowNum . ':AC' . ($rowNum + 1));

                $activeSheet->setCellValue('AD' . $rowNum, '4. Comments');
                $activeSheet->getStyle('AD' . $rowNum)->getFont()->setBold(true);
                $activeSheet->mergeCells('AD' . $rowNum . ':AD' . ($rowNum + 1));

                $columnNum = 'B';

                foreach (self::getTableElements() as $elementName => $elementDesc) {
                    $activeSheet->setCellValue(
                        $columnNum . ($rowNum + 1),
                        str_replace(
                            array('student_', 'instructor_', 'DV', 'AD'),
                            array('', '', 'D/V', 'Adm'),
                            $elementName
                        )
                    );
                    $columnNum++;
                }
                $rowNum += 2;
            }
            $activeSheet->setCellValue('A' . $rowNum, ($i * 2) . '-' . (($i * 2) + 2) . ' min');

            $columnNum = 'B';

            foreach (self::getTableElements() as $elementName => $elementDesc) {
                if (isset($data['table_' . $elementName][$i])) {
                    $activeSheet->getStyle($columnNum . $rowNum)->getFill()->setFillType(
                        PHPExcel_Style_Fill::FILL_SOLID
                    );
                    $activeSheet->getStyle($columnNum . $rowNum)->getFill()->getStartColor()->setRGB('000000');
                    $activeSheet->setCellValue($columnNum . $rowNum, 1);
                }
                $columnNum++;
            }

            if (isset($data['table_Eng'][$i])) {
                $activeSheet->setCellValue('AA' . $rowNum, $data['table_Eng'][$i]);
                $activeSheet->mergeCells('AA' . $rowNum . ':AC' . $rowNum);
            }

            if (isset($data['table_Comments'][$i])) {
                $activeSheet->setCellValue('AD' . $rowNum, $data['table_Comments'][$i]);
            }
            $rowNum++;
        }
        $activeSheet->getColumnDimension('A')->setWidth(9);
        $activeSheet->getColumnDimension('AD')->setWidth(25);

        $fileName = 'CDOP-' . strtotime($observation->date) . '-' . $data['observer_name'] . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        $objWriter->save('php://output');
        Yii::app()->end();
    }

    public function actionGraph($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
        $data = json_decode($observation->data, true);

        if (isset($_POST['GraphForm'])) {
            $this->render('graph', array('observation' => $observation, 'observationData' => $data, 'graphSettings' => $_POST['GraphForm']));
        } else {
            $model = new GraphForm;

            $timeArray = array();
            for ($i = 0; $i <= ($data['time'] + 2); $i += 2) {
                $timeArray[$i] = $i;
            }
            $this->render('graph-form', array('model' => $model, 'timeArray' => $timeArray));
        }
    }

    public function actionError()
    {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest) {
                echo $error['message'];
            } else {
                $this->render('error', $error);
            }
        }
    }

    public static function getTableElements()
    {
        return array(
            'student_L' => 'Listening',
            'student_Ind' => 'Individual thinking/problem solving',
            'student_CG' => 'Clicker question discussion',
            'student_WG' => 'Group worksheet activity',
            'student_OG' => 'Group activity',
            'student_AnQ' => 'Answering a question posed by instructor',
            'student_SQ' => 'Student asks question',
            'student_WC' => 'Class discussion',
            'student_Prd' => 'Making predictions (e.g. outcome of demo)',
            'student_SP' => 'Student presentation',
            'student_TQ' => 'Test/quiz',
            'student_W' => 'Waiting (instructor late, working on fixing AV problems, instructor otherwise occupied, etc.)',
            'student_O' => 'Other',
            'instructor_Lec' => 'Lecturing',
            'instructor_RtW' => 'Real-time writing',
            'instructor_FUp' => 'Instructor feedback on question/activity',
            'instructor_PQ' => 'Posing non-clicker question to students',
            'instructor_CQ' => 'Clicker question',
            'instructor_AnQ' => 'Listening to/answering student questions',
            'instructor_MG' => 'Moving through class and guiding student learning',
            'instructor_1o1' => 'Focus on small group of individuals',
            'instructor_DV' => 'Demo/video/photo/simulation',
            'instructor_AD' => 'Administration',
            'instructor_W' => 'Waiting (opportunity for instructor to be doing something and not doing so)',
            'instructor_O' => 'Other'
        );
    }

    /**
     * Cleans up an array to leave only the requested elements as per param typeToKeep
     *
     * @param $typeToKeep The type of data to keep ('student', 'instructor', 'Eng')
     * @param $array The data to be cleaned.
     * @return A cleaned up array.
     */
    public function cleanArray($typeToKeep, $array)
    {
        foreach ($array as $key => $value) {
            if (strpos($key, 'table_' . $typeToKeep) === false) {
                unset($array[$key]);
            }
        }
        return $array;
    }

    /**
     * Counts the data input as appropriate for a pie chart distribution of events.
     *
     * This converts the CDOP data to something that can be read by Google's
     * graphing API. The elements are formatted as: ['name', #], ['name', #], etc.
     *
     * @param $array The data to be counted.
     * @param $time_start The index in which to start counting for the array.
     * @param $time_end The index in which to end counting for the array.
     * @return An data set in the format as detailed above.
     */
    public function countForPieChartDist($array, $time_start, $time_end)
    {
        $key_start = $time_start / 2; // key = time / 2;
        $key_end = $time_end / 2;
        $return = array();

        foreach ($array as $key => $value) {
            foreach ($value as $keyb => $valueb) {
                if (($key_start > $keyb) || ($key_end < $keyb)) {
                    // This is not within our time range -- goodbye.
                    unset($array[$key][$keyb]);
                }
            }
            // ['name'], #] where 'name' has the table_ prefix removed.
            $return[] = "['".substr(str_replace(array('student_', 'instructor_'), '', $key), 6)."', ".count($array[$key])."]";
        }
        return implode(', ', $return);
    }

    // Same as above but for Eng codes
    public function countEngForPieChartDist($array, $time_start, $time_end)
    {
        $key_start = $time_start / 2; // key = time / 2;
        $key_end = $time_end / 2;
        $return = array();

        foreach ($array['table_Eng'] as $key => $value) {
            if (($key_start > $key) || ($key_end < $key)) {
                // This is not within our time range -- goodbye.
                unset($array['table_Eng'][$key]);
            }
        }
        foreach (array_count_values($array['table_Eng']) as $key => $value) {
            if ($key != "") {
                $return[] = "['".$key."', ".$value."]";
            }
        }
        return implode(', ', $return);
    }

    /**
     * array_map for multi-dimensional arrays
     *
     * @author qeremy (from: http://php.net/manual/en/function.array-map.php)
     * @param $fn The function that will modify each array element
     * @param $arr The array to modify
     * @return array The array with fn applied to each element
     */
    public function array_map_recursive($fn, $arr)
    {
        $rarr = array();
        foreach ($arr as $k => $v) {
            $rarr[$k] = is_array($v)
                ? $this->array_map_recursive($fn, $v)
                : call_user_func($fn, $v);
        }
        return $rarr;
    }

    /**
     * Converts a POSTed object to complete array form.
     *
     * @param $object The data to be converted.
     * @return The converted data.
     */
    public function objectToArray($object)
    {
        if (is_object($object)) {
            $object = get_object_vars($object);
        }
        return is_array($object)
            ? array_map(array(__CLASS__, __FUNCTION__), $object)
            : $object;
    }

    /**
     * Converts an array of POSTed data to JSON format and sanitizes the result.
     *
     * @param $array The data to be converted and sanitized.
     * @return A string in JSON form that is cleaned with htmlentities.
     */
    public function arrayToCleanJSON($array)
    {
        return json_encode($this->array_map_recursive('htmlentities', $array));
    }
}