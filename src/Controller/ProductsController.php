<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Product;
use Cake\I18n\DateTime;

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

        $this->set('products', $productsToDisplay);
    }

    /**
     * Initial method of the 'product_form.php'
     * @return void
     */
    public function productForm()
    {
    }

    /**
     * Finds the products that contain the user's provided keywords
     * @param string $searchKeywords
     * @return void
     */
    public function search()
    {
        $searchKeywords = $this->request->getQuery('search');

        if ($searchKeywords !== '') {
            $productsToDisplay = $this->Products->getProducts($searchKeywords);

            $this->set('products', $productsToDisplay);
            $this->set('searchKeywords', $searchKeywords);
            
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

        $product = new Product(
            $this->Products->getNextProductId(), 
            $data['name'], 
            $data['quantity'], 
            $data['price'],
            false, 
            new DateTime()
        );

        $product->customValidate();

        if ($product->hasErrors()) {
            $this->set('product', $product);
            $this->render($this->productFormPage);
        } else {
            $this->Products->addProduct($product);
            $this->redirect($this->homePage);
        }

        // validate Product before adding it to db
    }

    /**
     * Changes a property's value of a Product object
     * @param mixed $id
     * @param mixed $productProperty
     * @param mixed $newValue
     * @return void
     */
    public function edit(int $id, string $productProperty, mixed $newValue)
    {
        /* $productPropertyWithFirstCapital = ucfirst($productProperty);  // capitalise first letter
        $oldProduct = $this->getProductById($id);
        $newProduct = $oldProduct;
        $isProductChanged = $newProduct->{"set$productPropertyWithFirstCapital"}($newValue);
        
        if (!$isProductChanged) {
            $newProduct->setLastUpdatedAsNow();
            $this->products[array_search($oldProduct, $this->products)] = $newProduct;
            
        } else {
            // code to return an error message
        } */
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

