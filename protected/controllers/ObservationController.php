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
        $this->render('create');
    }

    public function actionPrint($id)
    {
        $observation = Observation::model()->find('id = :id', array(':id' => $id));
        $this->render('print', array('observation' => $observation));
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
        $this->render('graph', array('observation' => $observation));
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

    public function actionLogin()
    {
        $model = new LoginForm;

        if (isset($_POST['LoginForm'])) {
            $model->attributes = $_POST['LoginForm'];

            if ($model->validate() && $model->login()) {
                $this->redirect(Yii::app()->user->returnUrl);
            }
        }
        $this->render('login', array('model' => $model));
    }

    private static function getTableElements()
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
}