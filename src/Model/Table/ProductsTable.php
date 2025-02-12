<?php
declare(strict_types=1);

namespace App\Model\Table;

use App\Model\Entity\Product;
use Cake\Datasource\ConnectionInterface;
use Cake\ORM\Table;
use Cake\I18n\DateTime;
use Cake\Datasource\ConnectionManager;

class ProductsTable extends Table
{
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // sets up the information for this model to use for interacting with the database
        $this->setTable('products');
        $this->setPrimaryKey('id');
        $this->setConnection(ConnectionManager::get('default'));
    }

    /**
     * Gets the non-deleted products from the database
     * @param string $name - if provided, it fetches all products containing this param value
     * @param string $status - if provided, it fetches all products filtered by the provided status
     * @return array
     */
    public function getProductsQuery(string $name = null, string $status = null)
    {
        $conditions = ['isDeleted' => False];

        if ($name !== null) {
            $conditions['LOWER(name) LIKE'] = '%' . $name . '%';
        }

        if ($status !== null && $status !== 'All') {
            $conditions['status'] = $status;
        }

        $query = $this->find()->where($conditions);

        return $query;
    }

    /**
     * Gets a Product by the provided ID
     * @param int $id
     * @return \App\Model\Entity\Product
     */
    public function getProductById(int $id): Product
    {
        return $this->find()
            ->where(['id' => $id])
            ->limit(1)
            ->toArray()[0];
    }

    /**
     * Calculates the next unique ID to use when creating a
     * new Product object
     * @return float|int
     */
    public function getNextProductId()
    {
        $maxProductIdResult = $this->find()
            ->select('id')
            ->disableHydration()
            ->orderByDesc('id')
            ->limit(1)
            ->toArray();

        return $maxProductIdResult == [] ? 1 : $maxProductIdResult[0]['id'] + 1;
    }

    /**
     * Creates a new Product object from parameters that are user inputs
     * from the Add Product form in addition to validating those inputs.
     * @param string $name
     * @param int $quantity
     * @param float $price
     * @return Product
     */
    public function createNewProduct(string $name, int $quantity, float $price) {
        $newProduct = new Product([
            'id' => $this->getNextProductId(),
            'name' => $name,
            'quantity' => $quantity,
            'price' => $price,
            'isDeleted' => false,
            'lastUpdated' => new DateTime()
        ]);

        $newProduct->customValidate();

        return $newProduct;
    }

    /**
     * Inserts the values, from a provided Product object,
     * into the database.
     * @param \App\Model\Entity\Product $product
     * @return void
     */
    public function insertProduct(Product $product) {
        $this->getConnection()->insert($this->getTable(), [
            'name' => $product->getName(),
            'quantity' => $product->getQuantity(),
            'price' => $product->getPrice(),
            'status' => $product->getStatus(),
            'lastUpdated' => $product->getLastUpdated()
        ]);
    }

    /**
     * Updates the database with the provided Product object's
     * properties.
     * @param \App\Model\Entity\Product $product
     * @return void
     */
    public function updateProduct(Product $product) {
        $this->getConnection()->update(
            $this->getTable(), 
            [
                'name' => $product->getName(),
                'quantity' => $product->getQuantity(),
                'price' => $product->getPrice(),
                'status' => $product->getStatus(),
                'lastUpdated' => $product->getLastUpdated()
            ],
            ['id' => $product->getId()]
        );
    }
    
    /**
     * Marks the referenced product as deleted in the database
     * without permanently deleting it.
     * @param int $id
     * @return void
     */
    public function softDeleteProduct(int $id) {
        $this->getConnection()->update(
            $this->getTable(), 
            ['isDeleted' => true],
            ['id' => $id]
        );
    }
}