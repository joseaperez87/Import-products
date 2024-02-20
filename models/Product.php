<?php

class Product
{
    private $id;
    public $code;
    public $name;
    public $level1;
    public $level2;
    public $level3;
    public $price;
    public $quantity;
    public $price_sp;
    public $properties;
    public $joint_buys;
    public $unit;
    public $image;
    public $display_on_main;
    public $description;
    public $created_at;
    private $tablename;
    private $connection;

    function __construct()
    {
        $db = new Database();
        $this->connection = $db->pdo;
        $this->tablename = 'products';
    }

    function save()
    {
        try {
            $query = "INSERT INTO {$this->tablename} (code, name, level1, level2, level3, price, quantity, price_sp, properties, joint_buys, unit, image, display_on_main, description, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->connection->prepare($query);
            return $stmt->execute([
                $this->code,
                $this->name,
                $this->level1,
                $this->level2,
                $this->level3,
                $this->price,
                $this->quantity,
                $this->price_sp,
                $this->properties,
                $this->joint_buys,
                $this->unit,
                $this->image,
                $this->display_on_main,
                $this->description,
                $this->created_at
            ]);
        }catch (Exception $ex){
            throw new Exception($ex->getMessage());
        }
    }

    function getAll($start){
        try {
            $stmt = $this->connection->query("Select COUNT(id) as total From {$this->tablename}");
            $total = $stmt->fetch(PDO::FETCH_ASSOC)['total'];
            $pages = ceil($total/100);

            $query = "Select * From {$this->tablename} LIMIT {$start}, 100";
            $stmt = $this->connection->query($query);
            $products = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                $products[] = $row;
            }
            return ['pages' => $pages, 'products' => $products];
        }catch (Exception $ex){
            throw new Exception($ex->getMessage());
        }
    }

    function checkIfExists($code){
        $stmt = $this->connection->prepare("Select COUNT(id) as count From {$this->tablename} Where code = ?");
        $stmt->execute([$code]);
        $total = $stmt->fetch(PDO::FETCH_ASSOC);
        return (bool)$total['count'];
    }
}