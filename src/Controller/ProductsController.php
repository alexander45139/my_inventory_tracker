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
     * Initial method of the 'add_products.php' page to send data to
     * @return void
     */
    public function addProduct()
    {
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
        
        $this->render('/products/home');
    }
    
    /**
     * Adds a new Product only if the provided $id is unique
     */
    public function add()
    {
        $data = $this->request->getData();

        $product = new Product(
            $this->getNextProductId(), 
            $data['name'], 
            $data['quantity'], 
            $data['price'],
            false, 
            new DateTime()
        );

        // validate Product before adding it to db
        $this->validateProduct($product);

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
            "SELECT *
            FROM products
            WHERE ID = $id
            LIMIT 1"
        );

        return $this->createProductFromSQLResult($result);
    }

    private function validateProduct($product)
    {
        if ($product->getName()) {
            
        }
    }

    /**
     * Gets the non-deleted products from the database
     * @param string $name - if provided, it fetches all products containing this param value
     * @return array
     */
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

    /**
     * Calculates the next unique ID to use when creating a
     * new Product object
     * @return float|int
     */
    private function getNextProductId()
    {
        $maxProductIdResult = $this->query(
            "SELECT ID
            FROM products
            ORDER BY ID DESC
            LIMIT 1"
        );

        return $maxProductIdResult == [] ? 1 : $maxProductIdResult[0]['ID'] + 1;
    }

    /**
     * Creates and returns a Product object from the provided
     * result from an SQL query
     * @param array $result - a row returned from an SQL query
     * @return Product
     */
    private function createProductFromSQLResult($result): Product
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

    /**
     * Executes an SQL query in the 'cakephp_inventory_products'
     * database and returns the results
     * @param string $query
     * @return array
     */
    private function query(string $query)
    {
        $connection = ConnectionManager::get('default');

        $results = $connection->execute($query)->fetchAll('assoc');

        return $results;
    }
}