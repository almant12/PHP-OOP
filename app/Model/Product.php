<?php


abstract class Product{

    protected $id;
    protected $sku;
    protected $name;
    protected $price;
    protected $type;


    public function __construct($sku,$name,$price,$type){
        $this->sku = $sku;
        $this->name = $name;
        $this->price = $price;
        $this->type = $type;
    }

    abstract public function save();

    public function getCommonFields()
    {
        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'name' => $this->name,
            'price' => $this->price,
            'type' => $this->type
        ];
    }

    public function setId($id){
        $this->id = $id;
    }
    public function getId(){
        return $this->id;
    }

}