<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use Cake\TestSuite\TestCase;
use App\Model\Table\ProductsTable;
use App\Model\Entity\Product;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\Datasource\ConnectionManager;

/**
 * Unit tests for ProductsTable
 */
class ProductsTableTest extends TestCase
{
    use IntegrationTestTrait;

    protected ProductsTable $Products;

    public function setUp(): void
    {
        parent::setUp();

        $this->Products = $this->getTableLocator()->get('Products');
        $this->Products->setConnection(ConnectionManager::get('test'));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unset($this->Products);
    }

    /**
     * Test for createNewProduct method
     * @return void
     */
    public function testCreateNewProduct()
    {
        // Low Stock Product
        $name = 'Low Stock Product';
        $quantity = 9;
        $price = 200;
        $product = $this->Products->createNewProduct($name, $quantity, $price);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($quantity, $product->getQuantity());
        $this->assertEquals($price, $product->getPrice());
        $this->assertEquals('Low Stock' , $product->getStatus());

        // Out of Stock Product
        $name = 'Out of Stock Product';
        $quantity = 0;
        $price = 100;
        $product = $this->Products->createNewProduct($name, $quantity, $price);
        $this->assertEquals('Out of Stock', $product->getStatus());
    }

    /**
     * Test for insertProduct method
     * @return void
     */
    public function testInsertProduct()
    {
        $this->Products->insertProduct(
            $this->Products->createNewProduct('New Product', 2, 23.45)
        );
        $this->assertResponseSuccess('Successfully inserted new product into the database');
    }

    /**
     * Test for getProductsQuery method
     * @return void
     */
    public function testGetProductsQuery()
    {
        $products = $this->Products->getProductsQuery()->toArray();
        $this->assertResponseSuccess('Successfully fetched all products from the database');
        $this->assertNotEmpty($products);
    }
    
    /**
     * Test for getProductById method
     * @return void
     */
    public function testGetProductById()
    {
        $product = $this->Products->getProductById(1);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals(1, $product->getId());
    }

    /**
     * Test for getNextProductId method
     * @return void
     */
    public function testGetNextProductId()
    {
        $productId = $this->Products->getNextProductId();
        $this->assertIsInt($productId);
    }

    /**
     * Test for updateProduct method
     * @return void
     */
    public function testUpdateProduct()
    {
        $product = $this->Products->getProductById(1);
        
        $name = 'Updated here';
        $quantity = 15;
        $price = 0;
        $product->setName($name);
        $product->setQuantity($quantity);
        $product->setPrice($price);

        $this->Products->updateProduct($product);
        $this->assertEquals($name, $product->getName());
        $this->assertEquals($quantity, $product->getQuantity());
        $this->assertEquals($price, $product->getPrice());
    }

    /**
     * Test for softDeleteProduct method
     * @return void
     */
    public function testSoftDeleteProduct()
    {
        $this->Products->softDeleteProduct(1);

        $product = $this->Products->getProductById(1);
        $this->assertEquals(True, $product->getIsDeleted());
    }
}