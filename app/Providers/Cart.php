<?php 
namespace App;

class Cart
{
    public $products = null;
    public $totalPrice = 0;
    public $totalQuantity = 0;

    public function __construct($cart)
    {
        if ($cart) {
            $this->products = $cart->products;
            $this->totalPrice = $cart->totalPrice;
            $this->totalQuantity = $cart->totalQuantity;
        }
    }

    public function AddCart($product, $id)
    {
        $newProduct = ['quantity' => 0, 'price' => 0, 'productInfo' => $product];

        if ($this->products) {
            if (array_key_exists($id, $this->products)) {
                $newProduct = $this->products[$id];
            }
        }

        $newProduct['quantity']++;
        $newProduct['price'] = $newProduct['quantity'] * $product->price;

        $this->products[$id] = $newProduct;
        $this->totalPrice += $newProduct['price'];
        $this->totalQuantity++;
    }
}

?>