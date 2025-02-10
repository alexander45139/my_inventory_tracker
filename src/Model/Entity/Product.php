<?php
namespace App\Model\Entity;

use Cake\I18n\DateTime;

/**
 * Custom type that describes a Product object's stock status
 */ 
enum Status: string
{
    case InStock = "In Stock";
    case LowStock = "Low Stock";
    case OutOfStock = "Out of Stock";
}

/**
 * Product class containing the information of each inventory product
 */
class Product
{
    private int $id;
    private string $name;
    private int $quantity;
    private float $price;
    private Status $status;
    private bool $isDeleted;
    private DateTime $lastUpdated;

    public function __construct($id, $name, $quantity, $price, $isDeleted, $lastUpdated)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setPrice($price);
        $this->setIsDeleted($isDeleted);
        $this->setLastUpdated($lastUpdated);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setQuantity($quantity)
    {
        $this->quantity = (int) $quantity;

        if ($quantity > 10) {
            $this->status = Status::InStock;
        } else if ($quantity > 10) {
            $this->status = Status::InStock;
        } else if ($quantity >= 1 && $quantity <= 10) {
            $this->status = Status::LowStock;
        } else {
            $this->status = Status::OutOfStock;
        }
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function setPrice($price)
    {
        $this->price = (float) $price;
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getStatus()
    {
        return $this->status->value;
    }

    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    public function setLastUpdated($lastUpdated)
    {
        $this->lastUpdated = new DateTime($lastUpdated);
    }

    public function getLastUpdated() 
    {
        return $this->lastUpdated->format('Y-m-d H:i:s');
    }
}