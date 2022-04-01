<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'address', 'phone', 'nik'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getCustomers($request)
    {
        $name       = $request->name;
        $address    = $request->address;
        $nik        = $request->nik;
        $phone      = $request->phone;
        $items_per_page      = $request->items_per_page;

        $customers   = Customer::select();

        if($name){
            $customers = $customers->where('name','LIKE','%'.$name.'%');
        }
        if($address){
            $customers = $customers->where('address','LIKE','%'.$address.'%');
        }
        if($nik){
            $customers = $customers->where('nik','LIKE','%'.$nik.'%');
        }
        if($phone){
            $customers = $customers->where('phone','LIKE','%'.$phone.'%');
        }

        if($items_per_page){
            $customers = $customers->paginate($items_per_page);
        }else{
            $customers = $customers->paginate(10);
        }

        return $customers;
    }

    public function cekCustomers($request, $type, $param)
    {
        if($type == 'byNik'){
            $query   = Customer::where('nik', $param)->first();

        }else{
            $query   = Customer::where('id', $param)->first();
        }

        return $query;
    }

    public function insertCustomers($request)
    {
        $query = Customer::create([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'nik' => $request->nik
        ]);

        return $query;
    }

    public function getCustomersById($id)
    {
        $query = Customer::find($id);

        return $query;
    }

    public function updateCustomers($request, $id)
    {
        $query = Customer::where('id', $id)->update([
            'name' => $request->name,
            'address' => $request->address,
            'phone' => $request->phone,
            'nik' => $request->nik
        ]);

        return $query;
    }

    public function deleteCustomers($id)
    {
        $query = Customer::whereId($id)->delete();

        return $query;
    }
}
