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
    // static variable keeps count of the next unique ID to use when a new object is created
    static private int $idCount = 0;

    private int $id;
    private string $name;
    private int $quantity;
    private float $price;
    private Status $status;
    private bool $isDeleted;
    private DateTime $lastUpdated;

    public function __construct($name, $quantity, $price, $status)
    {
        $this->id = Product::$idCount;
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setPrice($price);
        $this->setStatus($status);
        $this->setIsDeleted(false);
        $this->setLastUpdatedAsNow();
        
        Product::$idCount++;
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

    public function setLastUpdatedAsNow()
    {
        $this->lastUpdated = new DateTime();
    }

    public function getLastUpdated() 
    {
        return $this->lastUpdated->format('Y-m-d H:i:s');
    }
}