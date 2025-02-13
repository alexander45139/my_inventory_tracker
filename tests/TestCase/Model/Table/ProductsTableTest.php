<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use Cake\ORM\TableRegistry;
use App\Model\Table\ProductsTable;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\Datasource\ConnectionManager;

class ProductsTableTest extends TestCase
{
    use IntegrationTestTrait;

    protected ProductsTable $Products;

    public function setUp(): void
    {
        parent::setUp();

        $this->Products = TableRegistry::getTableLocator()->get("Products");
        $this->Products->setConnection(ConnectionManager::get('test'));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->Products);
    }

    public function testProductsQuery()
    {
        $this->Products->getProductsQuery()->toArray();
    }
}