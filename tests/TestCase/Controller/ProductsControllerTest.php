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

use Cake\Core\Configure;
use Cake\TestSuite\Constraint\Response\StatusCode;
use Cake\TestSuite\IntegrationTestTrait;
use App\Controller\ProductsController;
use App\View\AppView;

/**
 * ProductsControllerTest class
 */
class ProductsControllerTest extends PagesControllerTest
{
    use IntegrationTestTrait;

    public function testRenderHome()
    {
        $this->get('/products/renderHome');
        //$this->assertResponseOk("Home page has loaded");
        $this->assertResponseSuccess();
        
        //$this->get('/products/search?search=Torch&status=All');
    }

    /**
     * Test productForm method
     * @return void
     */
    public function testProductForm()
    {
        $this->get('/products/product_form');
        $this->assertResponseOk();
    }

    public function testSearch()
    {

    }

    public function testAdd()
    {

    }

    public function testEdit()
    {

    }

    function testDelete()
    {

    }
}
