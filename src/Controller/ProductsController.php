<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\DataSource\Paginator;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends PagesController
{
    private string $homePage = '/products/home';
    private string $productFormPage = '/products/product_form';
    private int $productItemsLimitPerPage = 5;

    public function initialize(): void
    {
        parent::initialize();

        $productsQuery = $this->Products->getProductsQuery();

        $paginatedProducts = $this->paginate($productsQuery, [
            'limit' => $this->productItemsLimitPerPage,
            'page' => 1
        ]);

        $this->set('products', $paginatedProducts);
    }

    /**
     * Initial method of the 'product_form.php' that works out whether the form
     * is adding a new product or an edit of an existing product and sends that
     * data to the view.
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
            $productsQuery = $this->Products->getProductsQuery($searchKeywords, $filterStatus);

            $paginatedProducts = $this->paginate($productsQuery, [
                'limit' => $this->productItemsLimitPerPage,
                'page' => 1
            ]);

            $this->set('products', $paginatedProducts);
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
            (int) $data['quantity'], 
            (float) $data['price']
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
     * Updates a Product object with the user's submitted form
     * inputs.
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

