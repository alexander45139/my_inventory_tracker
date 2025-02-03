<?php
class ProductsController extends Controller
{
    public $products = [];

    public function index()
    {
        //
    }
    
    // add a new Product only if the provided $id is unique
    public function add($id, $name, $quantity, $price, $status)
    {
        if (array_search($id, array_column($this->products, "id")) !== false) {
            $product = new Product($id, $name, $quantity, $price, $status);
            array_push($products, $product);
            return true;
        }

        return false;
        
    }

    public function edit($id, Request $request)
    {
        $product = Product::find($id);
        return view("", compact("product"));
    }

    public function delete($id)
    {
        $product = Product::find($id);
        $product->setIsDeleted(true);
    }
}