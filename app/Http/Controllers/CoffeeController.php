<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use Session;
use PhpParser\JsonDecoder;
use App\Models\Coffee;
use App\Models\Order;
use App\Models\Cart;
use GuzzleHttp\Client;
use Auth;
class CoffeeController extends Controller
{
    //retrieve all products for admin side
    public function getProducts(){
        $coffees = Coffee::all();
        return view('admin.products', ['coffees'=> $coffees]);

    }
    public function getIndex(){
        $coffees = Coffee::all();
        return view('landing.index', ['coffees'=> $coffees]);

    }
    public function getShop(){

        $coffees = Coffee::all();
        return view('shop.index', ['coffees'=> $coffees]);
    }

    public function getAddToCart(Request $request, $id, $sizeIndex, $brew) {

        $coffee = Coffee::find($id);
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart = new Cart($oldCart);

    
        if ($coffee) {
            $sizes = $coffee->sizes;
    
            if ($sizes && array_key_exists($sizeIndex, $sizes)) {
                $selectedSize = $sizes[$sizeIndex];
                $cart->add($coffee, $coffee->id, $sizeIndex, $brew);
                
                $request->session()->put('cart', $cart);
                
                
            } else {
                return "Size not found at index $sizeIndex";
            }
        } else {
            return "Coffee item not found";
        }
        return redirect()->route('coffee.shop');
    }
    

    public function getReduce(Request $request, $combinedKey){
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        
        $cart->reduce($combinedKey);
        
        $request->session()->put('cart', $cart);
        
        if (count ($cart->items)>0) {
            Session::put('cart',$cart);
        }
        else {
            Session::forget('cart');
        }
        return redirect()->route('coffee.cart');

    }

    public function getIncrease(Request $request, $combinedKey){
        $oldCart = $request->session()->has('cart') ? $request->session()->get('cart') : null;
        $cart = new Cart($oldCart);
        
        $cart->increase($combinedKey);
        
        $request->session()->put('cart', $cart);
        
        // Redirect back to wherever you need
        return redirect()->route('coffee.cart');
    }
    public function getRemove($combinedKey){
        $oldCart = Session::has('cart') ? Session::get('cart') : null;
        $cart= new Cart($oldCart);
        $cart->remove($combinedKey);
        
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
        Auth::user()->cart = $cart;
       
        // Session::forget('cart');
        return view('shop.cart', ['coffees' => $cart->items, 'totalPrice'=> $cart->totalPrice]);
    }

    public function getcheckOutForm(){

        if (!Session:: has('cart')){
           
           
            return view('shop.cart');
        }
        $oldCart = Session::get('cart');
        $cart= new Cart($oldCart);

        // Session::forget('cart');
        return view('shop.checkout', ['coffees' => $cart->items, 'totalPrice'=> $cart->totalPrice]);
    }


    public function checkout(Request $request)
    {
        $cart = Session::get('cart'); // Assuming you've stored cart data in the session

        $shippingData = [
            'name' => $request->input('fname') . ' ' . $request->input('lname'),
            'address' => $request->input('address'),
            'email' => $request->input('email'),
            'phone' => $request->input('phone'),
            'moreInfo' => $request->input('moreInfo')
        ];
    
        Session::put('shippingData',$shippingData);
        // Construct the line items array based on your cart data' 

        $lineItems = [];
        foreach ($cart->items as $item) {
            $lineItems[] = [
                'amount' => intval(json_decode($item['item'], true)['sizes'][$item['size']]['price'] * 100), // Convert the price to cents and ensure it's an integer
                'currency' => 'PHP',
                'quantity' => $item['qty'],
                'name' => json_decode($item['item'], true)['sizes'][$item['size']]['label'] . ' ' . $item['item']['title']

                // Add other necessary attributes
            ];
        }

        // Construct the payload for creating a checkout session in Paymongo
        $payload = [
            'data' => [
                'attributes' => [
                  
                    'payment_method_types' => ['card','gcash','paymaya'], // Payment method allowed (e.g., 'card')
                    'cancel_url' => route('coffee.shop'), // Replace with your cancel URL
                    'success_url' => route('payment.success'),
                    'return_url' => route('coffee.shop'), // Replace with your return URL
                    'billing_address_collection' => 'required',
                    'send_email_receipt'=>true, 
                    'line_items' => $lineItems,
                    // Add other necessary attributes as required by Paymongo API
                ],
            ],
        ];
        $username = config('api_keys.PAYMONGO_SECRET_KEY');; // Replace with your Paymongo API key
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
                Session::put('checkout_id',  $responseData['data']['id']);                
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


    public function successPayment(Request $request)
    {
        if (!Session:: has('cart')){           
            return view('shop.cart');
        }
        

        $oldCart = Session::get('cart');
        $shippingData=Session::get('shippingData');
        $checkout_id=Session::get('checkout_id');
        $defaultOrderStatus= "Processing";
        $cart= new Cart($oldCart);

    

        $order= new Order();
        $order->fill($shippingData);
        $order->cart= serialize($cart);
        $order->checkout_id = $checkout_id;
        $order->orderStatus = $defaultOrderStatus;

        Auth::user()->orders()->save($order);

        $this->expireCheckout($checkout_id);

        Session::forget('cart');
        Session::forget('shippingData');
        Session::forget('checkout_id');
        return redirect()->route('coffee.shop');
    }

    public function expireCheckout($checkoutSessionId){
    
        $secretApiKey = config('api_keys.PAYMONGO_SECRET_KEY');

        $client = new Client([
            'base_uri' => 'https://api.paymongo.com/v1/checkout_sessions/checkout_session_id/expire/',
            'headers' => [
                'Authorization' => 'Basic ' . base64_encode($secretApiKey . ":"),
                'Content-Type' => 'application/json'
            ]
        ]);

        try {
            $response = $client->delete("checkouts/{$checkoutSessionId}");
            $statusCode = $response->getStatusCode();

            if ($statusCode === 200) {
                echo "Checkout session {$checkoutSessionId} has been expired.";
            } else {
                echo "Failed to expire checkout session.";
            }
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            echo "Error: " . $e->getMessage();
        }   
    }
}
