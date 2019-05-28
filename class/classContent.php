<?php
class Content{
    private $maxIndex;
    private $animalsRow = "";
    protected $currentIndex = 0;
    protected $animalsArray = array();
    protected $zebraRow = false;
    
    
    function __construct(){
        //$this->maxIndex = count($array['animals']);
        //$this->animalsArray = $array;
        
    }
    
    protected function createRow($rowContent,$class="",$id=""){
        if($this->zebraRow == true){
            $zebra =" style='background-color: #efefef;'";
        }else{
            $zebra ="";
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
        return '<div '.$divId.'class="row'.$divClass.'"'.$zebra.'>'.$rowContent.'</div>';
    }
    
    protected function createCell($rowContent,$size,$id="",$value=""){
        $idDiv = $id;
        $valueDiv = $value;
        
        if($id != ""){
            $idDiv = ' id = "'.$id.'"';
        }
        
        return '<div class="col-sm-'.$size.'"'.$idDiv.$valueDiv.'>'.$rowContent.'</div>';
    }
    

 
    protected function createInput($type,$id,$value="",$size){
        #if($value!=""){
            $value = ' value = "'.$value.'"';
        #}
        
        return $this->createCell('<input type="'.$type.'" class="form-control" id="'.$id.'" '.$value.'>',$size);
    }
 
    public function getHidenInput($id,$value){
        return $this->createInput("hidden",$id,$value,"12");
    }
    
    public function getComment($id){
        return '<div class="form-group">
                    <label for="'.$id.'">Comments to order</label>
                    <textarea class="form-control" id="'.$id.'" rows="7"></textarea>
                  </div>';
    }
    
    protected function getRadio($name,$array,$size){
            $div = '<div class="btn-group btn-group-toggle" data-toggle="buttons">';
            foreach($array as $key => $value){
                $div .= '<label class="btn btn-info">';
                    $div .= '<input type="radio" name="'.$name.'" value="'.$value['value'].'">'.$value['desc'];
                $div .= '</label>';
            }
                
            $div .= '</div>';
            return $this->createCell($div,$size);
    }
}
?>