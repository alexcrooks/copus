<?php

class GraphForm extends CFormModel
{
    public $graphType;
    public $startTime;
    public $endTime;

    public function rules()
    {
        return array(
            array('graphType, startTime, endTime', 'required'),
        );
    }
}