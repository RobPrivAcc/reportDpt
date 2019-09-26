<?php


class Growth implements JsonSerializable {
    private $name = null;
    private $data = array();
    private $growth = null;
    private $prevYear = null;
    private $currYear = null;

    public function setName($name)
    {
        if($this->name != $name) {
            $this->name = $name;
        }
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setAll($dataAll)
    {
        $this->name = $dataAll->name;
        $this->data = $dataAll->data;
        $this->growth = $dataAll->growth;

        return $this;
    }

    public function setDates($dates)
    {
        if(isset($this->data)){
            $this->setDataSet($dates);
        }
    }

    private function setDataSet($dates)
    {
        foreach ($dates as $key => $date){
            $this->data[$date['year']] = 0;
        }
        $keys = array_keys($this->data);
        $this->prevYear = $keys[0];
        $this->currYear = $keys[1];
    }

    public function getDataSet()
    {
        return $this->data;
    }

    public function setData($year,$value)
    {
        $this->data[$year] = $value;
        return $this;
    }



    public function getGrowth()
    {
        $this->setGrowth();
        return $this->growth;
    }

    public function getPrevYear(){
        return $this->prevYear;
    }

    public function getCurrentYear(){
        return $this->currYear;
    }

    public function setGrowth(){
        $result = 0;
        $isProc = '%';
        $values = array_values($this->data);

        $prev = $values[0];
        $current = $values[1];
        if($prev != 0){
            $result = (($current - $prev) / $prev) * 100;
        }elseif($current === $prev) {
            $result = 0;
            $isProc = '';
        }elseif($current > $prev && $prev == 0) {
            $result = 100;
        }else{
            $result = -100;
        }


        $this->growth = round($result,2).$isProc;
    }



    /**
     * Specify data which should be serialized to JSON
     * @link https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return array('name' => $this->name,
                     'data' => $this->data,
                     'growth' => $this->growth);
        // TODO: Implement jsonSerialize() method.
    }
}