<?php


class Table
{

    private $headerArray = array();
    private $cell = array();
    private $row = array();
    private $header = null;

    public function addHeader($head){
        if(is_array($head) == true){
            foreach ($head as $k => $v){
                array_push($this->headerArray, $v);
            }
        }else{
            $this->headerArray[] = $head;
        }
        return $this;
    }

    public function makeUpper()
    {
        $tmp = null;

        foreach ($this->headerArray as $key=>$value){

            $tmp[] = ucwords($value);
        }
        $this->headerArray = $tmp;
        return $this;
    }

    public function makeHeader($colspan = null)
    {
        $head = null;

        if($colspan != null){
            $colspan = ' colspan = "2"';
        }


        foreach ($this->headerArray as $key=>$value){

            $head .= '<th'.$colspan.'>'.$value.'</th>';
        }

        $this->header .= "<tr>".$head."</tr>";
        $this->headerArray = array();
        return $this;
    }

    public function addCell($cell)
    {

        if(is_array($cell) === true){
            $this->cell = $cell;
        }else{
            $this->cell[] = $cell;
        }

        return $this;
    }

    public function addRow(){
        if($this->cell != null){
            $this->row[] = $this->cell;
            $this->cell = array();
        }
    }

    public function getRow()
    {
        $row = null;

        foreach ($this->row as $index=>$v){
            $row .= "<tr>";
            foreach ($v as $key => $value){
                $row .= "<td>".$value."</td>";
            }
            $row .= "</tr>";
        }

        return $row;
    }

    public function getTable()
    {
        return '<table>'.$this->header.$this->getRow().'</table>';
    }

}