<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        try {
            
            $this->orders = JWTAuth::parseToken()->authenticate();

        } catch (JWTException $e) {

            return false;
        }
        
        $this->getOrder = new Order;

    }

    public function index(Request $request)
    {

        $orders   =  $this->getOrder->getOrders($request);
        
        return response()->json([
                'success' => true,
                'data' => $orders,
            ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('customer_id', 'total_price');
        $validator = Validator::make($data, [
            'customer_id' => 'required',
            'total_price' => 'required',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $order = $this->getOrder->insertOrders($request);

        if($order == true){
            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to add order.',
            ], 500);
        }
    }

    public function show($id)
    {
        $order = $this->getOrder->getOrdersById($id);
    
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order not found.'
            ], 400);
        }else{
            return response()->json([
                'success' => true,
                'data' => $order
            ], Response::HTTP_OK);
        }
    
        return $order;
    }

    public function edit(Order $order)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //Validate data
        $data = $request->only('order_date','total_price');
        $validator = Validator::make($data, [
            'total_price' => 'required',
            'order_date' => 'required|date_format:Y-m-d H:i:s',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ckOrder = $this->getOrder->cekOrders($id);
        if (!$ckOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order not found.'
            ], 400);
        }
        
        $order = $this->getOrder->updateOrders($request, $id);
        $newOrder = $this->getOrder->getOrdersById($id);
        
        //Order updated, return success response
        if($order == true){
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $newOrder
            ], Response::HTTP_OK);
        }else{ 
            return response()->json([
                'success' => false,
                'message' => 'Order not updated'
            ], 500);
        }
    }

    public function destroy(Order $order, $id)
    {
        $ckOrder = $this->getOrder->cekOrders($id);
        if (!$ckOrder) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Order not found.'
            ], 400);
        }

        $order = $this->getOrder->deleteOrders($id);

        if($order == true){
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully'
            ], Response::HTTP_OK);
        }else{ 
            return response()->json([
                'success' => false,
                'message' => 'Order not deleted'
            ], 500);
        }
    }
}