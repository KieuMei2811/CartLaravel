<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Session\Session;
class ShoppingCartController extends Controller
{
    public function Index(){
        $product = Product::all();
        return view('trangchu',compact('product'));			
    }
        
    public function AddCart($id)
    {
        $product = Product::find($id);
        if ($product) {
            $oldCart = session('Cart') ?: null;
            $newCart = new Cart($oldCart);
            $newCart->AddCart($product, $id);
    
            request()->session()->put('Cart', $newCart);
            dd(session('Cart'));
        }
        return view('cart',compact('newCart'));
    }
    
        
}
    

