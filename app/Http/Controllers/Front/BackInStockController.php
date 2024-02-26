<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\BackInStock;
use App\Models\Product;
use Validator;

class BackInStockController extends Controller
{

    public function notifyme(Request $request, $product_id)
    {
        $product = Product::find($product_id);
        $product_slug = $product->slug;

        /* Sometimes products become available when user is already writing his email to subscribe. */
        if($product->stock > 0) {
            return redirect()->route('front.product', $product_slug)->with('success', __('Good news! This product is already available!'));
        }

        $rules =
        [
            'email' => 'unique:back_in_stock,email',
            'agree_privacy_policy' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails()) {
            return redirect()->route('front.product', $product_slug)->withErrors($validator)->withInput();
        }

        try {
            $input['email'] = $request->email;
            $input['product_id'] = $product_id;

            $back_in_stock = new BackInStock();
            $back_in_stock->fill($input)->save();

            return redirect()->route('front.product', $product_slug)->with('success', __('You will be notified by e-mail when this Product is available again.'));
        } catch(\Exception $e){
            Log::error('back_in_stock_front_controller_error', [$e->getMessage()]);
            return redirect()->route('front.product', $product_slug)->with('unsuccess',__('Unknown error. Please contact Support Team.'));
        }


    }

}