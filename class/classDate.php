<?php
class DATE{
     
    private $dateToday = null;
    private $currentYearStart = null;
    private $datesArray = array();
    
    public function __construct(){
        $this->dateToday = date("Y-m-d");
        $this->currentYearStart = date("Y")."-01-01";
    }
    
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