<?php
    class CATEGORY{
        
        private $categoryArray = array();
        private $subCategoryArray = array();
        private $pdo=null;
        private $result = array();
        private $dateArray = array();
    
    
    function openConnection($dbConnectionArray){
            try{
                $this->pdo = new PDO($dbConnectionArray["server"],$dbConnectionArray["user"],$dbConnectionArray["password"]); 
            }
            catch (PDOException $e){
                    $this->pdo = new PDO($dbConnectionArray["localServer"],$dbConnectionArray["user"],$dbConnectionArray["password"]);
            }
            
            $this->getTypeSubtypeArray();
    }
        
    function setDateArray($array){
        $this->dateArray = $array;
    }
    
    function getTypeSubtypeArray(){
        $array = array();
        
        $sql = "SELECT [Type] ,[SubType] FROM [Types]";

        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        while($row = $query->fetch()){
            if($row["SubType"] != "."){
                $array[$row["Type"]][] = $row["SubType"];
            }
        }
        $this->categoryArray = $array;
    }
    
    function saleTypeDetails($date){
        foreach($this->dateArray as $k => $v){
            foreach($this->categoryArray as $key => $value){
                $sql = "SELECT SUM([QuantityBought] * [Selling Price]) as [value] 
                    FROM Orders 
                        inner join Stock on [Name of Item] = [NameOfItem] 
                        inner join [Days] on [Order Number] = OrderNo
                    WHERE 
                        [Date] > :dateStart AND
                        [Date] < :dateEnd AND
                        [Type of Item] = :type;";
                        
                    $paramArray = array(":dateStart" => $v['dateStart'],
                                        ":dateEnd" => $v['dateEnd'],
                                        ":type" => $key);
                                    
                    $query = $this->pdo->prepare($sql);
                    $query->execute($paramArray);
    
                while($row = $query->fetch()){
                    if($key != '-'){
                        $this->result[$key][$v['year']] = round($row['value'],2);
                    }
                }
            }    
        }
    }
    
    function getTypeArray(){
        foreach($this->result as $key => $value){
            $this->result[$key]['growth'] = $this->growth($value[$this->dateArray["prevYear"]["year"]],$value[$this->dateArray["currYear"]["year"]]);
        }
        return $this->result;
    }
    
    
    function saleSubTypeDetails($type){
        //print_r($this->dateArray);
        //print_r($this->categoryArray[$type]);
        foreach($this->dateArray as $k => $v){
            foreach($this->categoryArray[$type] as $key => $value){
//                 [:dateStart] => 2018-01-01 [:dateEnd] => 2018-05-24 [:type] => Bird [:subType] => Bedding
                $sql = "SELECT SUM([QuantityBought] * [Selling Price]) as [value]
                        FROM Orders
                            inner join Stock on [Name of Item] = [NameOfItem]
                            inner join [Days] on [Order Number] = OrderNo
                        WHERE
                            [Date] > :dateStart AND
                            [Date] < :dateEnd AND
                            [SubType] = :subType AND
                            [Type of Item] = :type;";
                        
                $paramArray = array(":dateStart" => $v['dateStart'],
                                    ":dateEnd" => $v['dateEnd'],
                                    ":type" => $type,
                                    ":subType" => $value);
                
                
                
                $query = $this->pdo->prepare($sql);
                $query->execute($paramArray);
                
                while($row = $query->fetch()){
                    if($key != '-'){
                        $this->result[$value][$v['year']] = round($row['value'],2);
                    }
                }
            }    
        }
        
    }
    
    
    function growth($prev, $current){
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
            $isProc = "";*/
        }else{
            $result = (($current-$prev)/$prev)*100;
        }
        return round($result,2).$isProc;
    }
    }
?>