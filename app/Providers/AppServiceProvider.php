<?php

namespace App\Providers;

use App\Models\Cart;
use Illuminate\Support\Facades\View;
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
        View::composer('*', function ($view) {

            $cartItems = [];

            $cartTotal = 0;

            if(auth()->check()){
                $cartItems = Cart::with([
                    'product.mainImage',
                    'product.category'
                ])
                ->where('user_id', auth()->id())
                ->get();

                $cartTotal = $cartItems->sum(function($item){
                    return $item->product->price * $item->quantity;
                });
            }

            $view->with([
                'globalCartItems' => $cartItems,
                'globalCartTotal' => $cartTotal,
            ]);
        });
    }
}
