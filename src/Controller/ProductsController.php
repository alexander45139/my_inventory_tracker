<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Product;
use App\Model\Entity\Status;
use Cake\Http\Response;

/**
 * The ProductsController class changes the Product objects
 */
class ProductsController extends PagesController
{
    public array $products;

    public function initialize(): void
    {
        parent::initialize();

        // Because there's no database, I have created some products here
        $this->products = [
            new Product("1", "Torch", 3, 7.45, Status::InStock),
            new Product("2", "Earphones", 4, 9.99, Status::LowStock)
        ];
    }

    public function display(string ...$path): ?Response
    {
        $this->set("products", $this->products);
        return parent::display(...$path);
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
            $this->set("products", $this->products);
        }
    }

    /* public function edit($id, Request $request)
    {
        $product = Product::find($id);
        return view("", compact("product"));
    } */

    /* public function delete($id)
    {
        $product = $this->products->get($id);
        $product->setIsDeleted(true);
    } */
}