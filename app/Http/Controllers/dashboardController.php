<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Auth;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Http\Requests;
use Session;
use PhpParser\JsonDecoder;
use App\Models\Coffee;
use App\Models\Order;
use App\Models\Cart;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use DB;
use Illuminate\Support\Facades\Storage;
use Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use App\Exceptions;

class dashboardController extends Controller
{
    public function getDashboard(){
        $orders = Order::all();

        $orders->transform(function ($order, $key) {
            $order->cart = unserialize($order->cart);
            //dd($order->cart);
            return $order;
        });
 
        return view('admin.dashboard', ['orders' => $orders]);

    }
 
    public function getAccounts(){
    $users = User::whereDoesntHave('roles', function ($query) {
        $query->where('role_id', 2);
    })->with('roles:id,name')->get(); 

    return view('admin.accounts', ['users' => $users]);
    }

    public function getAPIs(){

        return view('admin.apis');

    }
    
    public function accountDestroy($id){
        $users = User::find($id);
        $users->delete();
        return redirect()->route('accounts')->with('success', 'Removed Successfully');
    }
    public function getOrders(){
        $orders = Order::all();

        $orders->transform(function ($order, $key) {
            $order->cart = unserialize($order->cart);
            return $order;
        });
        

        $orders = $orders->sortByDesc('created_at')->sortBy(function ($order) {
            switch ($order->orderstatus) {
                case 'Processing':
                    return 1;
                case 'Serving':
                    return 2;
                case 'Order Completed':
                    return 3;
                case 'Cancelled':
                    return 4;
                default:
                    return 5;
            }
        });

        return view('admin.orders', ['orders' => $orders]);
    }


    public function updateStatus(Request $request, $id, $status){

        $order = order::findOrFail($id);
        $order->orderStatus = $status;
        $order->save();
        return redirect()->route('orders')->with('success', 'Updated Successfully');
    }

    public function getPaymentId($checkout_id) {
        $secretApiKey = config('api_keys.PAYMONGO_SECRET_KEY');
        $username = config('api_keys.PAYMONGO_SECRET_KEY');
        $password = '';
    
        $client = new Client();

        $response = $client->get('https://api.paymongo.com/v1/checkout_sessions/' . $checkout_id, [
            'auth' => [$username, $password],
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . base64_encode($secretApiKey . ":"),
            ],
        ]);
    
        $jsonData = json_decode($response->getBody(), true);

        if ($response->getStatusCode() === 200) {
            if (isset($jsonData["data"]["attributes"]["payments"][0]["id"])) {
                $paymentId = $jsonData["data"]["attributes"]["payments"][0]["id"];
                return $paymentId;
            } else {
                return redirect()->route('orders')->with('success', 'Payment ID not found in response');
            }
        } else {
            return redirect()->route('orders')->with('success', 'Failed to fetch payment details');
        }
    }

    public function getAmount($checkout_id) {
        $secretApiKey = config('api_keys.PAYMONGO_SECRET_KEY');
        $username = config('api_keys.PAYMONGO_SECRET_KEY');
        $password = '';
    
        $client = new Client();
        $response = $client->get('https://api.paymongo.com/v1/checkout_sessions/' . $checkout_id, [
            'auth' => [$username, $password],
            'headers' => [
                'accept' => 'application/json',
                'authorization' => 'Basic ' . base64_encode($secretApiKey . ":"),
            ],
        ]);
    
        $jsonData = json_decode($response->getBody(), true);
        if ($response->getStatusCode() === 200) {
            if (isset($jsonData['data']['attributes']['line_items'][0]['amount'])) {
                $amount = $jsonData['data']['attributes']['line_items'][0]['amount'];
                return $amount;
            } else {
                return redirect()->route('orders')->with('success', 'Payment ammount not found in response');
            }
        } else {
            return redirect()->route('orders')->with('success', 'Failed to fetch payment details');
        }
    }
    public function cancelOrder(Request $request, $id, $checkout_id){
        $this->getPaymentId($checkout_id);
        $this->getAmount($checkout_id);
        $payment_id = $this->getPaymentID($checkout_id);
        $amount= $this->getAmount($checkout_id);
        $secretApiKey = config('api_keys.PAYMONGO_SECRET_KEY');
        $payload = [
            'data' => [
                'attributes' => [
                    'payment_id'=> $payment_id,
                    'amount' => $amount,
                    "currency"=> "PHP",
                    'reason' => 'requested_by_customer',
                ],
            ],
        ];

        $username = config('api_keys.PAYMONGO_SECRET_KEY');; // Replace with your Paymongo API key
        $password = ''; // For basic auth, the password is empty
        $client = new Client();

        try {
            $response = $client->post('https://api.paymongo.com/refunds/', [
                'auth' => [$username, $password],
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => 'Basic ' . base64_encode($secretApiKey . ":"),
                    'content-Type' => 'application/json',
                ],
                'json' => $payload,
            ]);

            if ($response->getStatusCode() === 200) {
                $status = "Cancelled";
                $order = order::findOrFail($id);
                $order->orderStatus = $status;
                $order->save();
                return redirect()->route('orders')->with('success', 'Cancelled Successfully');
            } else {
                $errorMessage = $response->getBody()->getContents(); // Get the error message from the response
                return redirect()->route('orders')->with('errors', 'Error response from Paymongo: ' . $errorMessage);
            }
        } catch (\Exception $e) {
            return redirect()->route('orders')->with('errors', 'Exception occurred: ' . $e->getMessage());
        }

        
    }
    public function editProduct(Request $request, $id){

        $jsonData = [];
        $imagePath ="";
        if ($request->has('Editsize') && is_array($request->input('Editsize'))) {
            foreach ($request->input('Editsize') as $key => $size) {
                $jsonData[] = [
                    'label' => $size,
                    'price' => intval($request->input('Editprice')[$key] ?? 0),
                ];
            }
        }
     

        if ($request->hasFile('EditimagePath') && $request->file('EditimagePath')){
            
            $image = $request->file('EditimagePath');
            $fileName = $image->getClientOriginalName();
            $imagePath = 'src/images/' . $fileName;
            Storage::disk('public')->putFileAs('src/images', $image, $fileName);

            $image = $request->file('EditimagePath');
            $fileName = $image->getClientOriginalName();
        
            $image->storeAs('src/images', $fileName, 'public');

            $imagePath = 'src/images/' . $fileName;
        } else{
                    // Find the Coffee record based on an ID (assuming you have the ID)
            $coffee = Coffee::findOrFail($id);

            $coffee->title = $request->input('Edittitle');
            $coffee->sizes = $jsonData;

            // Save the changes
            $coffee->save();

            // Redirect with a success message
            return redirect()->route('products')->with('success', 'Updated Successfully');
        }

                // Find the Coffee record based on an ID (assuming you have the ID)
                $coffee = Coffee::findOrFail($id);

                // Update the attributes with new values
                $coffee->imagePath = $imagePath;
                $coffee->title = $request->input('Edittitle');
                $coffee->sizes = $jsonData;
        
                // Save the changes
                $coffee->save();
        
                // Redirect with a success message
                return redirect()->route('products')->with('success', 'Updated Successfully');

    }
    public function postProduct(Request $request){
        
        $validator = Validator::make($request->all(), [
            'imagePath' => 'required',
            'title' => 'required|unique:coffees',
            'label.*' => 'required',
            'price.*' => 'required|numeric',
        ]);
    
        if ($validator->fails()) {
            return redirect()->back()->with('error', 'Invalid Input');
        }
        $image = $request->file('imagePath');
        $fileName = $image->getClientOriginalName();
        
        Storage::disk('public')->putFileAs('src/images', $image, $fileName);
        
        $imagePath = 'src/images/' . $fileName;

        $jsonData = [];

        if ($request->has('size') && is_array($request->input('size'))) {
            foreach ($request->input('size') as $key => $size) {
                $jsonData[] = [
                    'label' => $size,
                    'price' => intval($request->input('price')[$key] ?? 0),
                ];
            }
        }

        if ($request->hasFile('imagePath')) {
            $image = $request->file('imagePath');
            $fileName = $image->getClientOriginalName();
    
            $image->storeAs('src/images', $fileName, 'public');
        
            $imagePath = 'src/images/' . $fileName;
        }
        
        $coffee = new Coffee([
            'imagePath' => $imagePath,
            'title' => $request->input('title'),
            'sizes' => $jsonData,
        ]);

        $coffee-> save();
   
        return redirect()->route('products')->with('success', 'Added Successfully');
    }
    public function Coffeedestroy($id){
        $coffee = Coffee::find($id);
        $coffee->delete();
        return redirect()->route('products')->with('success', 'Removed Successfully');
    }
}
