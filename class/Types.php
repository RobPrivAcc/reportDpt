<?php


class Types
{
    private $categoryArray = array();
    private $pdo = null;

    public function __construct($pdo){
        $this->pdo = $pdo;
        $this->setTypes();
        return $this;
    }

    public function setTypes()
    {
        $array = array();
        $sql = "SELECT [Type] ,[SubType] FROM [Types]";

        $query = $this->pdo->prepare($sql);
        $query->execute();

        while($row = $query->fetch()){
            if($row["SubType"] != "."){
                $this->categoryArray[$row["Type"]][] = $row["SubType"];
            }
        }

//        $this->categoryArray = $array;
        return $this;
    }

    public function getTypes()
    {
        return $this->categoryArray;
    }
}