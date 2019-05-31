<?php
//include_once("classDB.php");

class product extends PDOException{
    
    private $pdo=null;
    private $detailArray = array();
    

    //creating connection string to petco to getallheaders product list

    
    function openConnection($dbConnectionArray){
            try{
                $this->pdo = new PDO($dbConnectionArray["server"],$dbConnectionArray["user"],$dbConnectionArray["password"]); 
            }
            catch (PDOException $e){
               // var_dump($e);
                $this->pdo = new PDO($dbConnectionArray["localServer"],$dbConnectionArray["user"],$dbConnectionArray["password"]);
            }
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
        return $array;
    }
    
    function saleTypeDetails($date){
        $sql = "SELECT [Type of Item], SUM([QuantityBought] * [Selling Price]) as [value]
                FROM Orders
                    inner join Stock on [Name of Item] = [NameOfItem]
                    inner join [Days] on [Order Number] = OrderNo
                WHERE
                    [Date] > '".$date['dateStart']."' AND
                    [Date] < '".$date['dateEnd']."'
                GROUP BY [Type of Item]
                ORDER BY [Type of Item] ASC";
    echo $sql;
        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        $array = array();
        
        while($row = $query->fetch()){
            if($row['Type of Item'] != '-'){
                $array[] = array('type' => $row['Type of Item'], 'value' => round($row['value'],2));    
            }
            
        }
        return $array;
    }
    
    function saleSubTypeDetails($type,$subType,$date){
        $sql = "SELECT SUM([QuantityBought] * [Selling Price]) as [value], [SubType]
                FROM Orders
                    inner join Stock on [Name of Item] = [NameOfItem]
                    inner join [Days] on [Order Number] = OrderNo
                WHERE
                    [Date] > '".$date['dateStart']."' AND
                    [Date] < '".$date['dateEnd']."' AND
                    [SubType] = '".$subType."' AND
                    [Type of Item] = '".$type."'
                GROUP BY [SubType]
                ORDER BY [SubType] ASC;";
        
        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        $array = array();
        $value = 0;
        
        while($row = $query->fetch()){
            $value = round($row['value'],2);
        }
        return $value;
    }
    
     function saleProductDetails($type,$subType,$date){
        $sub = "";
        
        if($subType !=""){
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
        
        $query = $this->pdo->prepare($sql);
        $query->execute();
        
        
        $value = 0;
        $year = date("Y",strtotime($date['dateEnd']));
        while($row = $query->fetch()){
            $this->detailArray[$row['Name of item']][$year] = round($row['value'],2);
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
    
    function getSaleDetails(){
        return $this->detailArray;
    }

    function close(){
        $this->pdo = null;
    }
}
?>