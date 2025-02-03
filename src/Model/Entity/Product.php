<?php
// Custom type that describes a Product object's stock status
enum Status
{
    case InStock;
    case LowStock;
    case OutOfStock;
}

class Product
{
    private $id;
    private $name;
    private $quantity;
    private $price;
    private $status;
    private $isDeleted;

    public function __construct($id, $name, $quantity, $price, $status)
    {
        $this->setId($id);
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setPrice($price);
        $this->setStatus($status);
        $this->setIsDeleted(false);
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $length = strlen($name);

        if ($length >= 3 && $length <= 50)
        {
            $this->name = $name;
            return true;
        }

        return false;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setQuantity($quantity)
    {
        if ($quantity >= 0 && $quantity <= 1000)
        {
            $this->quantity = (int) $quantity;
            return true;
        }

        return false;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setPrice($price)
    {
        if ($price > 0 && $price <= 10000)
        {
            $this->price = (float) $price;
            return true;
        }
        
        return false;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    public function getIsDeleted()
    {
        return $this->isDeleted;
    }
}