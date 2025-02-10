<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Product;
use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable("products");
        $this->setPrimaryKey("ID");
    }

    /**
     * Executes an SQL query in the 'cakephp_inventory_products'
     * database and returns the results
     * @param string $query
     * @return array
     */
    private function sqlQuery(string $query)
    {
        $connection = $this->getConnection(); //ConnectionManager::get('default');

        $results = $connection->execute($query)->fetchAll('assoc');

        return $results;
    }

    /**
     * Gets a Product by the provided ID
     * @param int $id
     * @return \App\Model\Entity\Product
     */
    public function getProductById(int $id): Product
    {
        $result = $this->sqlQuery(
            "SELECT *
            FROM products
            WHERE ID = $id
            LIMIT 1"
        );

        return $this->createProductFromSQLResult($result);
    }

    public function validateProduct($product)
    {
        if ($product->getName()) {
            
        }
    }

    /**
     * Gets the non-deleted products from the database
     * @param string $name - if provided, it fetches all products containing this param value
     * @return array
     */
    public function getProducts($name = null)
    {
        $filterName = $name ? " AND Name LIKE '%$name%'" : "";

        $results = $this->sqlQuery(
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
    public function getNextProductId()
    {
        $maxProductIdResult = $this->sqlQuery(
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
            $result['LastUpdated']
        );
    }
}