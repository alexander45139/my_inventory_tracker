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
    public function initialize(): void
    {
        parent::initialize();

        $productsToDisplay = $this->Products->getProducts();

        $this->set('products', $productsToDisplay);
    }

    /**
     * Initial method of the 'add_products.php' page to send data to
     * @return void
     */
    public function addProduct()
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
        
        $productsToDisplay = $this->Products->getProducts($searchKeywords);

        $this->set('products', $productsToDisplay);
        $this->set('searchKeywords', $searchKeywords);
        
        $this->render('/products/home');
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
        } else {
            
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
        $this->Products->query(
            "UPDATE products
                SET IsDeleted = True
            WHERE ID = $id"
        );

        $this->redirect(['action' => 'display']);
    }

    

    
}

