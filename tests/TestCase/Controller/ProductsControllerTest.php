<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         1.2.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Test\TestCase\Controller;

use Cake\TestSuite\IntegrationTestTrait;

/**
 * ProductsControllerTest class
 */
class ProductsControllerTest extends PagesControllerTest
{
    use IntegrationTestTrait;

    public function testRenderHome()
    {
        $this->get('/products/renderHome');
        $this->assertResponseOk();
    }

    /**
     * Test 'productForm' method in ProductsController
     * @return void
     */
    public function testProductForm()
    {
        $this->get('/products/product_form');
        $this->assertResponseOk();
    }

    /**
     * Test 'add' method in ProductsController
     * @return void
     */
    public function testAdd()
    {
        // Quantity < 10 & Price > 100
        $this->post('/products/add', [
            'name' => 'Test Product 1',
            'quantity' => 9,
            'price' => 200
        ]);
        $this->assertResponseSuccess(
            'Successfully added a product that fits the price and quanity comparison.'
        );
        $this->assertRedirect('/products/home');

        // Quantity > 10 & Price > 100
        $this->post('/products/add', [
            'name' => 'Test Product 2',
            'quantity' => 12,
            'price' => 200
        ]);
        $this->assertRedirect('/products/product_form');

        // Name contains "promo" & Price < 50
        $this->post('/products/add', [
            'name' => 'Test Promo Product 1',
            'quantity' => 9,
            'price' => 44
        ]);
        $this->assertResponseSuccess(
            'Successfully added a "promo" product that is under £50.'
        );
        $this->assertRedirect('/products/home');

        // Name contains "promo" & Price > 50
        $this->post('/products/add', [
            'name' => 'Test Promo Product 2',
            'quantity' => 9,
            'price' => 98
        ]);
        $this->assertRedirect('/products/product_form');
    }

    /**
     * Test 'edit' method in ProductsController
     * @return void
     */
    public function testEdit(): void
    {
        // Quantity < 10 & Price > 100
        $this->post('/products/edit/1', [
            'name' => 'Edited Product',
            'quantity' => 9,
            'price' => 200
        ]);
        
        // Quantity > 10 & Price > 100
        $this->post('/products/edit/1', [
            'name' => 'Edited Product',
            'quantity' => 12,
            'price' => 200
        ]);
        $this->assertRedirect('/products/product_form');

        // Name contains "promo" & Price < 50
        $this->post('/products/edit/1', [
            'name' => 'Edited Promo Product',
            'quantity' => 9,
            'price' => 44
        ]);
        $this->assertResponseSuccess(
            'Successfully added a "promo" product that is under £50.'
        );
        $this->assertRedirect('/products/home');

        // Name contains "promo" & Price > 50
        $this->post('/products/edit/1', [
            'name' => 'Edited Promo Product',
            'quantity' => 9,
            'price' => 98
        ]);
        $this->assertRedirect('/products/product_form');
    }

    /**
     * Test 'search' method in ProductsController
     * @return void
     */
    public function testSearch()
    {
        // Status = All
        $this->get('/products/search?search=Product&status=All');
        $this->assertResponseOk();
        
        // Status = In Stock
        $this->get('/products/search?search=Product&status=In+Stock');
        $this->assertResponseOk();

        // Status = Low Stock
        $this->get('/products/search?search=Product&status=Low+Stock');
        $this->assertResponseOk();

        // Status = Out of Stock
        $this->get('/products/search?search=Product&status=Out+Of+Stock');
        $this->assertResponseOk();
    }
    
    /**
     * Test 'delete' method in ProductsController
     * @return void
     */
    public function testDelete()
    {
        $this->post('/products/delete/1');
        $this->assertResponseSuccess('Sucessfully deleted product');
    }
}
