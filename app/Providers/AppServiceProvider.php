<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        view()->composer('header',function($view){
            $loai_sp = ProductType::all();
            $view->with('loai_sp',$loai_sp);
        });

        view()->composer('header',function ($view) {
            if(Session('cart')){ // Nếu tồn tại Session Cart thì thực thi lệnh dưới
                $oldCart = Session::get('cart');
                $cart = new Cart($oldCart);
                $view->with(['cart' => Session::get('cart'),
                                        'product_cart' => $cart->items,
                                        'totalPrice' => $cart->totalPrice,
                                        'totalQty' => $cart->totalQty
                
                                        ]);
                                        }
        });
    }
}
