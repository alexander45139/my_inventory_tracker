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

    public function __construct($id, $name, $quantity, $price, $status, $isDeleted, $lastUpdated)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setPrice(price: $price);
        $this->setStatus($status);
        $this->setIsDeleted($isDeleted);
        $this->setLastUpdated($lastUpdated);
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
        }
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
        }
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
        }
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function setStatus($status)
    {
        $this->status = Status::from($status);
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