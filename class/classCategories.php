<?php
    class CATEGORY{
        
        private $categoryArray = array();
        private $subCategoryArray = array();
        private $pdo=null;
        private $result = array();
        private $dateArray = array();
        private $detailArray = array();
        private $shop = null;
        private $type = null;
    

    function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    function setDateArray($array){
        $this->dateArray = $array;
    }

    public function setShop($shop)
    {
        $this->shop = $shop;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    function getTypeSubtypeArray(){
        return $this->categoryArray;
    }


    function saleTypeDetails($type, $subType = '',$isProduct = false){
        $subTypeSql = '';
        $sqlName = '';
        $sqlGroup = '';
        $sqlSort = '';

        if($isProduct === true){
            $sqlName = '[Name of item], ';
            $sqlGroup = ' GROUP BY [Name of item]';
            $sqlSort = ' ORDER BY [Name of item] ASC';

        }



        $paramArray[':type'] = $type;

        if($subType != ''){
            $subTypeSql = ' [SubType] = :subType AND ';
            $paramArray[':subType'] = $subType;
        }

        foreach($this->dateArray as $k => $v){
            $paramArray[':dateStart'] = $v['dateStart'];
            $paramArray[':dateEnd'] = $v['dateEnd'];

                $sql = "SELECT {$sqlName}SUM([QuantityBought] * [Selling Price]) as [value]
                        FROM Orders 
                            inner join Stock on [Name of Item] = [NameOfItem] 
                            inner join [Days] on [Order Number] = OrderNo
                        WHERE 
                            [Date] > :dateStart AND
                            [Date] < :dateEnd AND
                            {$subTypeSql}
                            [Type of Item] = :type
                            {$sqlGroup}
                            {$sqlSort};";


                $query = $this->pdo->prepare($sql);
                $query->execute($paramArray);

                $value = 0;


                while($row = $query->fetch()){


                    $value = $row['value'];
                    if($type != '-'){
                        if($isProduct == false) {
                            $this->result[$v['year']] = round($value, 2);
                        }else{
                            //$this->result[] = ->setData($v['year'], round($value,2));
                            $this->result[$row['Name of item']][$v['year']] = round($value,2);
                        }
                    }
                }

        }

        //var_dump($this->result);
/*        echo '<pre>' . var_export($this->result, true) . '</pre>';
        return;

        if($isProduct === true){

            foreach ($this->result as $name => $values){
                echo $name.'<br/>';
                $this->result[$name]['growth'] = $this->growth($values);
            }

        }*/

        //$this->result['growth'] = $this->growth($this->result);
       //var_dump($this->result);

        return $this->result;
    }
    
    function getTypeArray(){
        foreach($this->result as $key => $value){
            $this->result[$key]['growth'] = $this->growth($value[$this->dateArray["prevYear"]["year"]],$value[$this->dateArray["currYear"]["year"]]);
        }
        return $this->result;
    }

    function saleProductDetails($type,$subType,$date){
        $sub = "";
        if(isset($subType)){
            $sub = "[SubType] = '".$subType."' AND";
        }
        $sql = "SELECT [Name of item], SUM([QuantityBought] * [Selling Price]) as [value]
                FROM Orders
                    inner join Stock on [Name of Item] = [NameOfItem]
                    inner join [Days] on [Order Number] = OrderNo
                WHERE
                    [Date] > '".$date['dateStart']."' AND
                    [Date] < '".$date['dateEnd']."' AND
                    ".$sub."
                    [Type of Item] = '".$type."'
                GROUP BY [Name of item]
                ORDER BY [Name of item] ASC;";
          echo $sql;
          return;
        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        $value = 0;
        $year = date("Y",strtotime($date['dateEnd']));
        while($row = $query->fetch()){
            $this->detailArray[$row['Name of item']][$year] = round($row['value'],2);
        }
    }    
    
    
/*    function growth($prev, $current){
        $result = 0;
        $isProc = "%";
        if($prev == 0){
            if($prev != $current){
                $result = 100;    
            }else{
                $result = $current-$prev;
            }
            
            /*
            $result = $current-$prev;
            $isProc = "";
        }else{
            $result = (($current-$prev)/$prev)*100;
        }
        return round($result,2).$isProc;
    }*/

        function growth($data){
            $result = 0;
            $isProc = "%";
            $values = array_values($data);

            $prev = $values[0];
            $current = $values[1];
            if($prev !=0 && $current != 0) {
                if ($prev === 0) {
                    if ($prev != $current) {
                        $result = 100;
                    } else {
                        $result = $current - $prev;
                    }

                    /*
                    $result = $current-$prev;
                    $isProc = "";*/
                } else {
                    $result = (($current - $prev) / $prev) * 100;
                }
            }else{
                $result = 0;
            }
            return round($result,2).$isProc;
        }
    
    function getSaleDetails(){
        return $this->detailArray;
    }
}