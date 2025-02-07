<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Product;
use App\Model\Entity\Status;
use Cake\Datasource\ConnectionManager;#
use Cake\I18n\DateTime;
use Cake\Http\Response;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends PagesController
{
    public function initialize(): void
    {
        parent::initialize();

        $productsToDisplay = $this->getProducts();

        $this->set('products', $productsToDisplay);
    }

    /**
     * Finds the products that contain the user's provided keywords
     * @param string $searchKeywords
     * @return void
     */
    public function search()
    {
        $searchKeywords = $this->request->getQuery('search');
        
        $productsToDisplay = $this->getProducts($searchKeywords);

        $this->set('products', $productsToDisplay);
        $this->set('searchKeywords', $searchKeywords);
        
        $this->render('/pages/home');
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
        $product = new Product(
            $this->getNextProductId(), 
            $name, 
            $quantity, 
            $price, 
            $status, 
            false, 
            new DateTime()
        );

        // validate Product before adding it to db

        $this->redirect(['action' => 'display']);
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
        $this->query(
            "UPDATE products
                SET IsDeleted = True
            WHERE ID = $id"
        );

        $this->redirect(['action' => 'display']);
    }

    /**
     * Gets a Product by the provided ID
     * @param int $id
     * @return \App\Model\Entity\Product
     */
    private function getProductById(int $id): Product
    {
        $result = $this->query(
            "SELECT TOP 1 *
            FROM products
            WHERE ID = $id"
        );

        return $this->createProductFromSQLResult($result);

    }

    private function getProducts($name = null)
    {
        $filterName = $name ? " AND Name LIKE '%$name%'" : "";

        $results = $this->query(
            "SELECT *
            FROM products
            WHERE IsDeleted = False"
            . $filterName
        );

        $products = [];

        foreach ($results as $result) {
            array_push($products, $this->createProductFromSQLResult($result));
        }

        return $products;
    }

    private function getNextProductId()
    {
        $maxProductIdResult = $this->query(
            "SELECT TOP 1 ID
            FROM products
            ORDER BY ID DESC"
        );

        return $maxProductIdResult == [] ? 1 : $maxProductIdResult['ID'] + 1;
    }

    private function createProductFromSQLResult(array $result): Product
    {
        return new Product(
            $result["ID"],
            $result['Name'],
            $result['Quantity'],
            $result['Price'],
            $result['Status'],
            $result['IsDeleted'],
            $result['LastUpdated']
        );
    }

    private function query(string $query)
    {
        $connection = ConnectionManager::get('default');

        $results = $connection->execute($query)->fetchAll('assoc');

        return $results;
    }
}