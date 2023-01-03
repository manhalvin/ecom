<?php

namespace App\Http\Controllers\Demo;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DemoCartController extends Controller
{
    public function add($id)
    {
        // Session::forget('cart');
        $session_id = substr(md5(microtime()), rand(0, 26), 5);
        $product = Product::where('product_id', $id)->first();

        $qty = 1;
        $cart = Session::get('cart');
        // dd($cart);
        if (isset($cart['cart']) && array_key_exists($id, $cart['cart']['buy'])) {
            $qty = $cart['cart']['buy'][$id]['qty'] + 1;
            Session::put('cart', $cart);
        }

        $cart['cart']['buy'][$id] = array(
            'session_id' => $session_id,
            'id' => $product->product_id,
            'title' => $product->product_name,
            'price' => $product->product_price,
            'image' => $product->product_image,
            'qty' => $qty,
            'sub_total' => $product->product_price * $qty,
        );
        Session::put('cart', $cart);

        Session::save();
        $this->updateInfoCart();
        return redirect()->route('demo.cart.index');
    }

    public function updateInfoCart()
    {
        $cart = Session::get('cart');
        if (isset($cart['cart'])) {
            $numOrder = 0;
            $total = 0;
            foreach ($cart['cart']['buy'] as $item) {
                $numOrder += $item['qty'];
                $total += $item['sub_total'];
            }

            $cart['cart']['info'] = array(
                'num_order' => $numOrder,
                'total' => $total,
            );

            return $cart['cart']['info'];
        }
    }

    public function index()
    {
        $cartInfo = $this->updateInfoCart();

        $cart = Session::get('cart');
        $numOrder = 0;
        $getTotalCart = 0;
        if (isset($cart['cart'])) {
            $cartBuy = $cart['cart']['buy'];
            $numOrder = $cartInfo['num_order'];
            $getTotalCart = $cartInfo['total'];
            return view('demo.cart.index', compact('cartBuy', 'numOrder', 'getTotalCart'));
        }
        return view('demo.cart.index', compact('cart', 'numOrder', 'getTotalCart'));
    }

    public function delete($id)
    {
        $cart = Session::get('cart');
        if (isset($cart)) {
            if (!empty($id)) {
                unset($cart['cart']['buy'][$id]);
            } else {
                unset($cart['cart']);
            }
            Session::put('cart', $cart);
            Session::save();
            $this->updateInfoCart();
            return redirect()->route('demo.cart.index');
        }
    }

    public function deleteAll()
    {
        $cart = Session::get('cart');
        // dd($cart);
        unset($cart['cart']);
        Session::put('cart', $cart);
        Session::save();
        $this->updateInfoCart();
        return redirect()->route('demo.cart.index');
    }

    public function update(Request $request)
    {
        $cart = Session::get('cart');
        if ($request->input('btn_update_cart')) {
            $qty = $request->input('qty');
            foreach ($qty as $id => $newQty) {
                $cart['cart']['buy'][$id]['qty'] = $newQty;
                $cart['cart']['buy'][$id]['sub_total'] = $newQty * $cart['cart']['buy'][$id]['price'];
            }
        }
        Session::put('cart', $cart);
        Session::save();
        $this->updateInfoCart();
        return redirect()->route('demo.cart.index');
    }

    public function currency_format($number, $suffix = 'đ')
    {
        if (!empty($number)) {
            return number_format($number, 0, ',', '.') . "{$suffix}";
        }
    }

    public function updateAjax(Request $request)
    {
        $id = $request->input('id');
        $qty = $request->input('qty');

        $product = Product::where('product_id', $id)->first();
        $cart = Session::get('cart');

        if ($cart['cart'] && array_key_exists($id, $cart['cart']['buy'])) {
            $cart['cart']['buy'][$id]['qty'] = $qty;

            $sub_total = $qty * $product->product_price;
            $cart['cart']['buy'][$id]['sub_total'] = $sub_total;

            Session::put('cart', $cart);
            Session::save();
            $this->updateInfoCart();

            $total = $this->updateInfoCart()['total'];

            $data = [
                'sub_total' => $this->currency_format($sub_total),
                'total' => $this->currency_format($total),
                'num_order' =>  $this->updateInfoCart()['num_order']
            ];

            return response()->json(['data' => $data]);

        }
    }

}
