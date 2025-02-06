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
    public function initialize(): void
    {
        parent::initialize();

        // session will store the Product objects while the browser is open
        $session = $this->request->getSession();

        // if products aren't stored in the session
        if (!$session->check("products")) {
            // Because there's no database, I have created some products here
            $products = [
                new Product("Torch", 3, 7.45, Status::InStock),
                new Product("Earphones", 4, 9.99, Status::LowStock)
            ];
            $session->write("products", $products);
        } else {
            $products = $session->read("products");
        }
        
        $this->set("products", array_filter($products, fn($pr) => $pr->getIsDeleted() === false));
    }

    public function display(string ...$path): ?Response
    {
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
    public function add(string $name, int $quantity, float $price, Status $status)
    {
        $product = new Product($name, $quantity, $price, $status);
        array_push($products, $product);
    }

    /**
     * Changes a property's value of a Product object
     * @param mixed $id
     * @param mixed $productProperty
     * @param mixed $newValue
     * @return void
     */
    public function edit(int $id, string $productProperty, mixed $newValue)
    {
        /* $productPropertyWithFirstCapital = ucfirst($productProperty);  // capitalise first letter
        $oldProduct = $this->getProductById($id);
        $newProduct = $oldProduct;
        $isProductChanged = $newProduct->{"set$productPropertyWithFirstCapital"}($newValue);
        
        if (!$isProductChanged) {
            $newProduct->setLastUpdatedAsNow();
            $this->products[array_search($oldProduct, $this->products)] = $newProduct;
            
        } else {
            // code to return an error message
        } */
        
    }

    /**
     * Marks a Product object as deleted
     * @param mixed $id
     * @return void
     */
    public function delete(int $id)
    {
        $oldProduct = $this->getProductById($id);
        $newProduct = $oldProduct;

        $newProduct->setIsDeleted(true);
        $newProduct->setLastUpdatedAsNow();

        $allProducts = $this->getProductsFromSession();
        $allProducts[
            array_search($oldProduct, $this->getProductsFromSession())
        ] = $newProduct;
        $this->setProductsFromSession($allProducts);
    }

    private function getProductsFromSession(): array
    {
        $session = $this->request->getSession();
        return $session->read("products");
    }

    private function setProductsFromSession($products)
    {
        $session = $this->request->getSession();
        $session->write("products", $products);
        $this->redirect(['action' => 'display']);
    }

    /**
     * Gets a Product by the provided ID
     * @param int $id
     * @return \App\Model\Entity\Product
     */
    private function getProductById(int $id): Product
    {
        return $this->getProductsFromSession()[$id];
    }

    private function validateProduct($id) {
        
    }
}