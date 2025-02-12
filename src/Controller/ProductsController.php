<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends PagesController
{
    private string $homePage = '/products/home';
    private string $productFormPage = '/products/product_form';

    public function initialize(): void
    {
        parent::initialize();

        $productsToDisplay = $this->Products->getProducts();

        /* $this->paginate($this->Products->find(), [
            'limit' => 5,
            'page' => 1
        ]); */

        $this->set('products', $productsToDisplay);
    }



    /**
     * Initial method of the 'product_form.php'
     * @param int $id
     * @return void
     */
    public function productForm(int $id = null)
    {
        $product = ($id !== null) ? $this->Products->getProductById($id) : null;

        $this->set('formType', ($id === null) ? 'add' : 'edit');
        $this->set('product', $product);
    }

    /**
     * Finds the products that contain the user's provided keywords
     * @param string $searchKeywords
     * @return void
     */
    public function search()
    {
        $searchKeywords = $this->request->getQuery('search');
        $filterStatus = $this->request->getQuery('status');

        if ($searchKeywords !== '' || $filterStatus !== 'All') {
            $productsToDisplay = $this->Products->getProducts($searchKeywords, $filterStatus);

            $this->set('products', $productsToDisplay);
            $this->set('searchKeywords', $searchKeywords);
            $this->set('filterStatus', $filterStatus);
            
            $this->render($this->homePage);
        } else {
            $this->redirect($this->homePage);
        }
    }
    
    /**
     * Adds a new Product only if the provided $id is unique
     */
    public function add()
    {
        $data = $this->request->getData();

        $product = $this->Products->createNewProduct(
            $data['name'], 
            $data['quantity'], 
            $data['price']
        );

        if ($product->hasErrors()) {
            $this->set('formType', 'add');
            $this->set('product', $product);

            $this->render($this->productFormPage);
        } else {
            $this->Products->insertProduct($product);

            $this->redirect($this->homePage);
        }
    }

    /**
     * Changes a property's value of a Product object
     * @param int $id
     * @return void
     */
    public function edit(int $id)
    {
        $data = $this->request->getData();

        $product = $this->Products->getProductById($id);

        $product->setName($data['name']);
        $product->setQuantity((int) $data['quantity']);
        $product->setPrice((float) $data['price']);

        $this->Products->updateProduct($product);

        $this->redirect($this->homePage);
    }

    /**
     * Marks a Product object as deleted
     * @param mixed $id
     * @return void
     */
    public function delete(int $id)
    {
        $this->Products->softDeleteProduct($id);
        
        $this->redirect($this->homePage);
    }
}

