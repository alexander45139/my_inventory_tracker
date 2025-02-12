<?php
namespace App\Model\Entity;

use Cake\I18n\DateTime;
use Cake\ORM\Entity;

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
class Product extends Entity
{
    // The columns of the products table
    private int $id;
    private string $name;
    private int $quantity;
    private float $price;
    private Status $status;
    private bool $isDeleted;
    private DateTime $lastUpdated;

    public function __construct($properties, $options = [])
    {
        $this->id = $properties['id'];
        $this->setName($properties['name']);
        $this->setQuantity($properties['quantity']);
        $this->setPrice($properties['price']);
        $this->setIsDeleted($properties['isDeleted']);
        $this->setLastUpdated($properties['lastUpdated']);
    }

    /* public function __construct($id, $name, $quantity, $price, $isDeleted, $lastUpdated)
    {
        $this->id = $id;
        $this->setName($name);
        $this->setQuantity($quantity);
        $this->setPrice($price);
        $this->setIsDeleted($isDeleted);
        $this->setLastUpdated($lastUpdated);
    } */

    public function getId()
    {
        return $this->id;
    }

    public function setName(string $name)
    {
        $this->name = $name;

        // Set object's last update as now
        $this->lastUpdated = new DateTime();
    }

    public function getName()
    {
        return $this->name;
    }
    
    public function setQuantity(int $quantity)
    {
        $this->quantity = $quantity;

        // Set object's last update as now
        $this->lastUpdated = new DateTime();

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

    public function setPrice(float $price)
    {
        $this->price = $price;

        // Set object's last update as now
        $this->lastUpdated = new DateTime();
    }

    public function getPrice()
    {
        return $this->price;
    }

    public function getStatus()
    {
        return $this->status->value;
    }

    public function setIsDeleted(bool $isDeleted)
    {
        $this->isDeleted = $isDeleted;

        // Set object's last update as now
        $this->lastUpdated = new DateTime();
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

    /**
     * Validates the rules of the product's values and assigns error messages
     * if those rules are not met for this object
     * @return void
     */
    public function customValidate() {
        if ($this->price > 100 && $this->quantity > 10) {
            $this->setError(
                'priceAndQuantity',
                'Products with a price > 100 must have a minimum quantity of 10.'
            );
        }
        
        if (stripos(mb_strtolower($this->name), 'promo') !== false
            && $this->price >= 50
        ) {
            $this->setError(
                'promoName',
                'Products with a name containing "promo" must have a price < 50.'
            );
        }
    }
}