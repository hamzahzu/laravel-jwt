<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'customer_id', 'order_date', 'total_price'
    ];

    public function getOrders($request)
    {
        $name       = $request->name;
        $address    = $request->address;
        $nik        = $request->nik;
        $phone      = $request->phone;
        $order_id      = $request->order_id;
        $items_per_page      = $request->items_per_page;

        $orders   = Order::selectRaw('orders.id as order_id, order_date, total_price, name, address, nik, phone')
        ->join('customers','orders.customer_id','=','customers.id');

        if($name){
            $orders = $orders->where('name','LIKE','%'.$name.'%');
        }
        if($address){
            $orders = $orders->where('address','LIKE','%'.$address.'%');
        }
        if($nik){
            $orders = $orders->where('nik','LIKE','%'.$nik.'%');
        }
        if($phone){
            $orders = $orders->where('phone','LIKE','%'.$phone.'%');
        }
        if($order_id){
            $orders = $orders->where('orders.id','=',$order_id);
        }

        if($items_per_page){
            $orders = $orders->paginate($items_per_page);
        }else{
            $orders = $orders->paginate(10);
        }

        return $orders;
    }

    public function cekOrders($id)
    {
        $query   = Order::where('id', $id)->first();

        return $query;
    }

    public function insertOrders($request)
    {
        $query = Order::create([
            'customer_id' => $request->customer_id,
            'order_date' => date('Y-m-d H:i:s'),
            'total_price' => $request->total_price,
        ]);

        return $query;
    }

    public function getOrdersById($id)
    {
        $query = Order::find($id);

        return $query;
    }

    public function updateOrders($request, $id)
    {
        $query = Order::where('id', $id)->update([
            'total_price' => $request->total_price,
            'order_date' => $request->order_date
        ]);

        return $query;
    }

    public function deleteOrders($id)
    {
        $query = Order::whereId($id)->delete();

        return $query;
    }
}
