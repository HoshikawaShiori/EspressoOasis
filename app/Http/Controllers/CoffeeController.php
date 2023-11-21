<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;

use App\Models\Coffee;
use App\Models\Cart;
use GuzzleHttp\Client;
class CoffeeController extends Controller
{
    public function getIndex(){

        $coffees = Coffee::all();
        return view('shop.index', ['coffees'=> $coffees]);
    }

    public function getAddToCart(Request $request, $id){
        $coffee = Coffee::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart= new Cart($oldCart);
        $cart->add($coffee, $coffee->id);

        $request->session()->put('cart',$cart);
        return redirect()->route('coffee.shop');
    }

    public function getReduce($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart= new Cart($oldCart);
        $cart->reduce($id);

        if (count ($cart->items)>0) {
            Session::put('cart',$cart);
        }
        else {
            Session::forget('cart');
        }
        return redirect()->route('coffee.cart');

    }

    public function getIncrease($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart= new Cart($oldCart);
        $cart->increase($id);

        Session::put('cart',$cart);
        return redirect()->route('coffee.cart');
    }
    public function getRemove($id){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart= new Cart($oldCart);
        $cart->remove($id);
        
        if (count ($cart->items)>0) {
            Session::put('cart',$cart);
        }
        else {
            Session::forget('cart');
        }
        return redirect()->route('coffee.cart');
    }
    public function getCart(){
        if (!Session:: has('cart')){
            return view('shop.cart');
        }
        $oldCart = Session::get('cart');
        $cart= new Cart($oldCart);
        return view('shop.cart', ['coffees' => $cart->items, 'totalPrice'=> $cart->totalPrice]);
    }

    public function checkout()
    {
        $cart = Session::get('cart'); // Assuming you've stored cart data in the session

        // Construct the line items array based on your cart data
        $lineItems = [];
        foreach ($cart->items as $item) {
            $lineItems[] = [
                'amount' => intval($item['price'] * 100), // Convert the price to cents and ensure it's an integer
                'currency' => 'PHP',
                'description' => $item['item']['title'],
                'quantity' => $item['qty'],
                'name' => $item['item']['title'],
                // Add other necessary attributes
            ];
        }

        // Construct the payload for creating a checkout session in Paymongo
        $payload = [
            'data' => [
                'attributes' => [
                    'payment_method_types' => ['card','gcash','paymaya'], // Payment method allowed (e.g., 'card')
                    'cancel_url' => route('coffee.shop'), // Replace with your cancel URL
                    'return_url' => 'http://localhost:8000/', // Replace with your return URL
                    'billing' => [
                        'description' => 'Checkout description details',
                    ],
                    'line_items' => $lineItems,
                    // Add other necessary attributes as required by Paymongo API
                ],
            ],
        ];
        $username = 'sk_test_xEpXHanDYhXfVwY5XyXNUtBY'; // Replace with your Paymongo API key
        $password = ''; // For basic auth, the password is empty

        // Make the request to create a checkout session in Paymongo
        $client = new Client();
        $response = $client->post('https://api.paymongo.com/v1/checkout_sessions', [
            'auth' => [$username, $password], // Set up Basic Authentication
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => $payload,
        ]);

        if ($response->getStatusCode() === 200) {
            $responseData = json_decode($response->getBody(), true);

            if (isset($responseData['data']['attributes']['checkout_url'])) {
                $checkoutUrl = $responseData['data']['attributes']['checkout_url'];
                // Redirect the user to Paymongo checkout URL
                return redirect($checkoutUrl);
            } else {
                // Handle the case where checkout_url is not found in the response
                return 'Checkout URL not found';
            }
        } else {
            // Handle the error response from Paymongo
            return 'Error response from Paymongo';
        }
    }
}
