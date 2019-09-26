 <?php
 class dbConnection{
    
    /*
        0 - Petzone
        1 - Donaghmede
        2 - Petco
        3 - Charlestown
        4 - Swords
    */
    
    private $user = null;
    private $password = null;
    private $index;
    private $shopsArray = null;
    
    private $dbConnectionArray;
  
     function __construct($xmlArray){
    
          $this->user = $xmlArray['login']['user'];
          $this->password = $xmlArray['login']['password'];
          $array = $xmlArray['shop'];
          for($i=0; $i < count($array); $i++){
             $this->dbConnectionArray[$i] = array(
                    'shopName' => $array[$i]['shopName'],
                    'server' => 'sqlsrv:Server='.$array[$i]['external_IP'].',1317;Database='.$array[$i]['dbName'],
                    'localServer' => 'sqlsrv:Server='.$array[$i]['internal_IP'].';Database='.$array[$i]['dbName'],// SANGOTILL3
                    'user' => $this -> user,
                    'password' => $this -> password,
                    'shopNo' => $array[$i]['shop_No']
                );
          }

         $this->setShopsArray();
    }
  
    public function getMaxIndex(){
        return count($this -> dbConnectionArray);
    }


    public function getShopName(){
        return $this -> dbConnectionArray[$this -> index]['shopName'];
    }
    
    public function getShopNo(){
         return $this -> dbConnectionArray[$this -> index]['shopNo'];
    }

    public function getDbConnection($index){
        $this -> index = $index;
        if($this -> index > $this -> getMaxIndex()){
            return "Out of range. Max index =".count($this -> dbConnectionArray);
        }else{
//            return $this -> dbConnectionArray[$this -> index];
            $this-> connect($this -> index);
        }
     }

     public function getDbConnectionByName($shopName){
          foreach (array_values($this->dbConnectionArray) as $i => $value) {
            if(strtolower($shopName) == $value['shopName']){
               return $this-> connect($i);
            }
          }
     }
     
     public function setShopsArray(){
         for ($i = 0; $i < $this -> getMaxIndex(); $i++){
             $this->shopsArray[] = array('shopName' => $this -> dbConnectionArray[$i]['shopName'],'shopNo' => $this -> dbConnectionArray[$i]['shopNo']);
         }
     }


     public function getShopsArray(){
         if(isset($this->shopsArray)){
             return $this->shopsArray;
         }
     }

     public function getShopsName(){
         $tmp = null;

         foreach ($this->shopsArray as $key=>$value){
             $tmp[] = $value['shopName'];
         }
         return $tmp;
     }

     public function connect($index)
     {
         $pdo = null;
         try{
             $pdo = new PDO($this -> dbConnectionArray[$index]["server"],$this -> dbConnectionArray[$index]["user"],$this -> dbConnectionArray[$index]["password"]);
         }
         catch (PDOException $e){
             $pdo = new PDO($this -> dbConnectionArray[$index]["localServer"],$this -> dbConnectionArray[$index]["user"],$this -> dbConnectionArray[$index]["password"]);
         }
         return $pdo;
     }

}