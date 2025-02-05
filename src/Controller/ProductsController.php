<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Product;
use App\Model\Entity\Status;
use Cake\Http\Response;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends PagesController
{
    public array $products;

    public function initialize(): void
    {
        parent::initialize();

        // Because there's no database, I have created some products here
        $this->products = [
            new Product("Torch", 3, 7.45, Status::InStock),
            new Product("Earphones", 4, 9.99, Status::LowStock)
        ];
    }

    public function display(string ...$path): ?Response
    {
        $this->set("products", $this->products);
        return parent::display(...$path);
    }
    
    /**
     * Adds a new Product only if the provided $id is unique
     * @param int $id
     * @param string $name
     * @param int $quantity
     * @param float $price
     * @param Status $status
     */
    public function add($name, $quantity, $price, $status)
    {
        $product = new Product($name, $quantity, $price, $status);
        array_push($products, $product);
        $this->set("products", $this->products);
    }

    /**
     * Changes a property's value of a Product object
     * @param mixed $id
     * @param mixed $productProperty
     * @param mixed $newValue
     * @return void
     */
    public function edit($id, $productProperty, $newValue)
    {
        $productPropertyWithFirstCapital = ucfirst($productProperty);  // capitalise first letter
        $oldProduct = $this->getProductById($id);
        $newProduct = $oldProduct;
        $isProductChanged = $newProduct->{"set$productPropertyWithFirstCapital"}($newValue);
        
        if (!$isProductChanged) {
            $newProduct->setLastUpdatedAsNow();

            $this->products[array_search($oldProduct, $this->products)] = $newProduct;
            $this->set("products", $this->products);
        } else {
            // code to return an error message
        }
        
    }

    /**
     * Marks a Product object as deleted
     * @param mixed $id
     * @return void
     */
    public function delete($id): void
    {
        $this->edit($id, "isDeleted", true);
    }

    /**
     * Gets a Product by the provided ID
     * @param int $id
     * @return \App\Model\Entity\Product
     */
    private function getProductById($id): Product
    {
        return array_filter($this->products, fn($p) => $p->id === $id)[0];
    }

    private function validateProduct($id) {
        
    }
}