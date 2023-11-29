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

class dashboardController extends Controller
{
    public function getDashboard(){
        return view('admin.dashboard');

    }

    public function getOrders(){
        $orders = Order::all();
        $orders-> transform(function($order, $key){
            $order->cart=unserialize($order->cart);
            return $order;
        });
        return view('admin.orders', ['orders'=> $orders]);
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
        
        // Store the file in the public directory
        Storage::disk('public')->putFileAs('src/images', $image, $fileName);
        
        // Get the path to the stored file
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
        
            // Store the file in the 'public' disk
            $image->storeAs('src/images', $fileName, 'public');
        
            // Get the full path to the stored file
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
