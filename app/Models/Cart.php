<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    public $items=null;
    public $totalQty=0;
    public $totalPrice= 0;

    public function __construct($oldCart){
        if($oldCart){
            $this->items = $oldCart->items;
            $this->totalQty = $oldCart->totalQty;
            $this->totalPrice = $oldCart->totalPrice;
            $this->belongsTo(User::class);
        }
    }
    public function cart() {
        return  $this->belongsTo(User::class);
       }

    public function add($item, $id, $size, $brew) {
        $combinedKey = "$id-$size-$brew";
    
        if (isset($this->items[$combinedKey])) {
            $storedItem = $this->items[$combinedKey];
        } else {
            $storedItem = [
                'oID'=>$combinedKey,
                'qty' => 0,
                'size' => $size,
                'price' => $item->sizes[$size]['price'],
                'brew' => $brew,
                'item' => $item,
                
            ];
        }
    
        $storedItem['qty']++;
        $storedItem['price'] = $item->sizes[$size]['price'] * $storedItem['qty'];
        $this->items[$combinedKey] = $storedItem;
        $this->totalQty++;
        $this->totalPrice += $item->sizes[$size]['price'];
    }
    

    public function increase($combinedKey) {
    if (isset($this->items[$combinedKey])) {
        $this->items[$combinedKey]['qty']++;
        $this->items[$combinedKey]['price'] += $this->items[$combinedKey]['item']->sizes[$this->items[$combinedKey]['size']]['price'];
        $this->totalQty++;
        $this->totalPrice += $this->items[$combinedKey]['item']->sizes[$this->items[$combinedKey]['size']]['price'];
    }
    }

    public function reduce($combinedKey) {
        if (isset($this->items[$combinedKey])) {
            $this->items[$combinedKey]['qty']--;
            $this->items[$combinedKey]['price'] -= $this->items[$combinedKey]['item']->sizes[$this->items[$combinedKey]['size']]['price'];
            $this->totalQty--;
            $this->totalPrice -= $this->items[$combinedKey]['item']->sizes[$this->items[$combinedKey]['size']]['price'];

            if ($this->items[$combinedKey]['qty'] <= 0) {
                unset($this->items[$combinedKey]);
            }
        }
    }

    public function remove($combinedKey){
        $this->totalQty-=$this->items[$combinedKey]['qty'];
        $this->totalPrice-=$this->items[$combinedKey]['price'];
        unset($this->items[$combinedKey]);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
