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
    private int $productItemsLimitPerPage = 5;

    public function initialize(): void
    {
        parent::initialize();

        $this->renderHome();
    }

    /**
     * When entering the home page, the non-deleted products are paginated and displayed.
     * @param string $searchKeywords - keywords entered by the user in the search bar to filter the products
     * @param string $filterStatus - stock status selected by the user to filter the products
     * @return void
     */
    public function renderHome(string $searchKeywords = null, string $filterStatus = null): void
    {
        $productsQuery = $this->Products->getProductsQuery($searchKeywords, $filterStatus);

        $paginatedProducts = $this->paginate($productsQuery, [
            'limit' => $this->productItemsLimitPerPage,
            'page' => 1
        ]);

        $this->set('products', $paginatedProducts);
        $this->set('searchKeywords', $searchKeywords);
        $this->set('filterStatus', $filterStatus);
        
        $this->render($this->homePage);
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

        $this->render($this->productFormPage);
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
            $this->renderHome($searchKeywords, $filterStatus);
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

        $product->customValidate();

        // product isn't inserted into database if custom validation is not met
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

        $product->customValidate();

        if ($product->hasErrors()) {
            $this->set('formType', 'edit');
            $this->set('product', $product);

            $this->render($this->productFormPage);
        } else {
            $this->Products->updateProduct($product);
            
            $this->redirect($this->homePage);
        }
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

