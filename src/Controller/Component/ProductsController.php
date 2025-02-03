<?php
namespace App\Controller\Component;

use App\Model\Entity\Product;
use App\Model\Entity\Status;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends Controller
{
    public array $products = [];

    public function index()
    {
        $this->set("products", $this->products);
    }
    
    /**
     * Adds a new Product only if the provided $id is unique
     * @param int $id
     * @param string $name
     * @param int $quantity
     * @param float $price
     * @param Status $status
     */
    public function add($id, $name, $quantity, $price, $status)
    {
        if (array_search($id, array_column($this->products, "id")) !== null) {
            $product = new Product($id, $name, $quantity, $price, $status);
            array_push($products, $product);
        }
        
    }

    public function edit($id, Request $request)
    {
        /* $product = Product::find($id);
        return view("", compact("product")); */
    }

    public function delete($id)
    {
        $product = $this->products->get($id);
        $product->setIsDeleted(true);
    }
}