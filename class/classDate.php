<?php
class DATE{
     
    private $dateToday;
    private $currentYearStart;
    private $datesArray = array();
    
    //public function __construct($dateStart = date("Y-m-d"), $dateEnd = date("Y")."-01-01"){
    //    $this->dateToday = $dateStart;
    //    $this->currentYearStart = $dateEnd;
    //}
    public function __construct($dateStart = '', $dateEnd = '')
    {
        if ($dateStart === '' && $dateEnd === ''){
            $this->dateToday = date("Y-m-d");
            $this->currentYearStart = date("Y")."-01-01";
        }else{
            $this->currentYearStart = $dateStart;
            $this->dateToday = $dateEnd;
        }
    }

//    public function dateInit($dateStart = '', $dateEnd = ''){
//        if ($dateStart == '' && $dateEnd == ''){
//            $this->dateToday = date("Y-m-d");
//            $this->currentYearStart = date("Y")."-01-01";
//        }else{
//            $this->currentYearStart = $dateStart;
//            $this->dateToday = $dateEnd;
//        }
//
//    }
    
    public function getCurrentYearDate(){
        return array('dateStart' => $this->currentYearStart, 'dateEnd'=> $this->dateToday, 'year' => date("Y"));
    }
    
    public function getPreviousYearDate(){
        return array('dateStart' => date("Y-m-d",strtotime($this->currentYearStart." -1 year")), 'dateEnd'=> date("Y-m-d",strtotime($this->dateToday." -1 year")), 'year' => date("Y",strtotime($this->currentYearStart." -1 year")));
    }
    
    public function getYear(){
        return array('lastYear' => date("Y",strtotime($this->currentYearStart." -1 year")), 'currentYear'=> date("Y"));
    }
    
    public function getDates(){
        $this->datesArray['prevYear'] = $this->getPreviousYearDate();
        $this->datesArray['currYear'] = $this->getCurrentYearDate();
        
        return $this->datesArray;
    }
}
?>