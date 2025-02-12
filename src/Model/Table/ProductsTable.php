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

        $this->setTable('products');
        $this->setPrimaryKey('id');
        $this->setConnection(ConnectionManager::get('default'));
    }

    /**
     * Gets the non-deleted products from the database
     * @param string $name - if provided, it fetches all products containing this param value
     * @return array
     */
    public function getProducts(string $name = null, string $status = null)
    {
        $conditions = ['isDeleted' => False];

        if ($name !== null) {
            $conditions['name LIKE'] = '%' . $name . '%';
        }

        if ($status !== null) {
            $conditions['status'] = $status;
        }

        $results = $this->find()->where($conditions)->toArray();

        return $results;
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

    public function createNewProduct($name, $quantity, $price) {
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

    public function insertProduct(Product $product) {
        $this->getConnection()->insert($this->getTable(), [
            'name' => $product->getName(),
            'quantity' => $product->getQuantity(),
            'price' => $product->getPrice(),
            'status' => $product->getStatus(),
            'lastUpdated' => $product->getLastUpdated()
        ]);
    }

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
    
    public function softDeleteProduct(int $id) {
        $this->sqlQuery(
            "UPDATE products
                SET IsDeleted = True
            WHERE ID = $id"
        );
    }
}