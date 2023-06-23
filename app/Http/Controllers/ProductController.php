<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Cart;
use App\Models\Bill;
// use App\Models\Wishlist;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function Cart(){
        return view('cart');
    }
    // Bắt buộc đăng nhập mới mua hàng
    public function getAddToCart(Request $req, $id)
    {
        if (Session::has('user')) {
            if (Product::find($id)) {
                $product = Product::find($id);
                $oldCart = Session('cart') ? Session::get('cart') : null;
                $cart = new Cart($oldCart);
                $cart->add($product, $id);
                $req->session()->put('cart', $cart);
                return redirect()->back();
            } else {
                return '<script>alert("Không tìm thấy sản phẩm này.");window.location.assign("/");</script>';
            }
        } else {
            return '<script>alert("Vui lòng đăng nhập để sử dụng chức năng này.");window.location.assign("/login");</script>';
        }
    }
      
    public function getDelItemCart($id){
        $oldCart = Session::has('cart')?Session::get('cart'):null;
        $cart = new Cart($oldCart);
        $cart->removeItem($id);
        if(count($cart->items)>0){
        Session::put('cart',$cart);

        }
        else{
            Session::forget('cart');
        }
        return redirect()->back();
    }		
    //----------------------------CHECKOUT-----------------------//
    
    public function getCheckout()
    {
      if(Session::has('cart')){
        $oldCart = Session::get('cart');
        $cart = new Cart($oldCart);
        return view('page.checkout')->with(['cart' => Session::get('cart'),
                                                        'product_cart'=>$cart->items,
                                                        'totalPrice'=> $cart->totalPrice,
                                                        'totalQty'=>$cart->totalQty]);
      }  else{
        return redirect('page');
      }
    }

	public function postCheckout(Request $req){
		$cart = Session::get('cart');
		$customer = new Customer;
		$customer->name = $req->full_name;
		$customer->gender = $req->gender;
		$customer->email = $req->email;
		$customer->address = $req->address;
		$customer->phone_number = $req->phone;

		if(isset($req->notes)){
			$customer->note = $req->notes;
		} else{
			$customer->note = "Không có ghi chú gì";
		}

		$customer->save();

		$bill = new Bill;
		$bill->id_customer = $customer->id;
		$bill->date_order = date('Y-m-d');
		$bill->total = $cart->totalPrice;
		$bill->payment = $req->payment_method;
		if(isset($req->notes)){
			$bill->note = $req->notes;
		}else{
			$bill->note = "Không có ghi chú gì";
		}
		$bill->save();

		foreach($cart->items as $key =>$value){
			$bill_detail = new BillDetail;
			$bill_detail->id_bill = $bill->id;
			$bill_detail->id_product = $key;
			$bill_detail->quantity = $value['qty'];
			$bill_detail->unit_price = $value['price'] / $value['qty'];
		}

		Session::forget('cart');
		$wishlists = Wishlist::where('id_user', Session::get('user')->id)->get();
		if(isset($wishlists)){
			foreach($wishlists as $element){
				$element->delete();
			}
		}

	}

}
