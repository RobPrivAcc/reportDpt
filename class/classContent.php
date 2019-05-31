<?php
class Content{
    private $maxIndex;
    private $animalsRow = "";
    protected $currentIndex = 0;
    protected $animalsArray = array();
    protected $zebraRow = false;
    protected $cellArray = array();
    protected $div = null;
    
    
    
    //function __construct(){
    //    //$this->maxIndex = count($array['animals']);
    //    //$this->animalsArray = $array;
    //    
    //}
    
    public function createRow($class="",$id="",$zebra = true){
        $rowContent = "";
        
        if($zebra == true){
            if($this->zebraRow == true){
                $zebra =" style='background-color: #efefef;'";
            }else{
                $zebra ="";
            }
        }
        
        $divClass = "";
        
        if($class !=""){
            $divClass = " ".$class;    
        }
        
        $divId = "";
        
        if($id !==""){
            $divId = " id='".$id."' ";    
        }        
        
        $this->zebraRow = !$this->zebraRow;
        
        for($i=0; $i < count($this->cellArray); $i++){
            $rowContent .= $this->cellArray[$i];
        }

        $this->cellArray = array();
        $this->div .= '<div '.$divId.'class="row'.$divClass.'"'.$zebra.'>'.$rowContent.'</div>';
    }
    
    public function createCell($rowContent,$size,$class="",$id="",$value=""){
        $idDiv = $id;
        $valueDiv = $value;
        
        if($id != ""){
            $idDiv = ' id = "'.$id.'"';
        }
        
        //return '<div class="col-sm-'.$size.'"'.$idDiv.$valueDiv.'>'.$rowContent.'</div>';
        $this->cellArray[] = '<div class="col-sm-'.$size.$class.'"'.$idDiv.$valueDiv.'>'.$rowContent.'</div>';
    }
    

 
    public function createInput($type,$id,$value="",$size){
        #if($value!=""){
            $value = ' value = "'.$value.'"';
        #}
        
        return $this->createCell('<input type="'.$type.'" class="form-control" id="'.$id.'" '.$value.'>',$size);
    }
 
    public function getHidenInput($id,$value){
        $idDiv = ' id = "'.$id.'"';
        $valueDiv = $value;
        $this->div .= '<input type="hidden" class="form-control" id="'.$id.'" value = "'.$value.'"/>';
    }
    
    public function getComment($id){
        return '<div class="form-group">
                    <label for="'.$id.'">Comments to order</label>
                    <textarea class="form-control" id="'.$id.'" rows="7"></textarea>
                  </div>';
    }
    
    public function getRadio($name,$array,$size){
            $div = '<div class="btn-group btn-group-toggle" data-toggle="buttons">';
            foreach($array as $key => $value){
                $div .= '<label class="btn btn-info">';
                    $div .= '<input type="radio" name="'.$name.'" value="'.$value['value'].'">'.$value['desc'];
                $div .= '</label>';
            }
                
            $div .= '</div>';
            return $this->createCell($div,$size);
    }
    
    public function showResult(){
        return $this -> div;
    }
}
?>