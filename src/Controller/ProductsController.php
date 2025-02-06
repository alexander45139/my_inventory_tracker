<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Product;
use App\Model\Entity\Status;
use Cake\Datasource\ConnectionManager;#
use Cake\Log\Log;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends PagesController
{
    public array $products;

    public function initialize(): void
    {
        parent::initialize();

        $this->fetchAllProducts();
    }

    /**
     * Finds the products that contain the user's provided keywords
     * @param string $keywords
     * @return void
     */
    public function search(string $keywords) {
        //
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
        $product = new Product($name, $quantity, $price, $status, false, new DateTime());
        array_push($this->products, $product);
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

    /**
     * Gets the Product objects array stored in the session
     * @return mixed|null
     */
    private function getProductsFromSession()
    {
        $session = $this->request->getSession();
        return $session->read("products");
        
    }

    /**
     * Sets the Product objects array stored in the session
     * @param mixed $products
     * @return void
     */
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

    private function fetchAllProducts()
    {
        $results = $this->query('SELECT * FROM products WHERE IsDeleted = False');

        $this->products = [];

        foreach ($results as $result) {
            array_push($this->products, new Product(
                $result['Name'],
                $result['Quantity'],
                $result['Price'],
                $result['Status'],
                $result['IsDeleted'],
                $result['LastUpdated']
            ));
        }
        
        $this->set('products', $this->products);
    }

    private function query(string $query) {
        $connection = ConnectionManager::get('default');

        $results = $connection->execute($query)->fetchAll('assoc');

        return $results;
    }
}