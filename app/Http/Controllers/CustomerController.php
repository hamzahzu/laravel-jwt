<?php
namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    protected $user;
 
    public function __construct()
    {
        try {
            
            $this->customers = JWTAuth::parseToken()->authenticate();

        } catch (JWTException $e) {

            return false;
        }
        
        $this->getCustomers = new Customer;

    }

    public function index(Request $request)
    {

        $customers   =  $this->getCustomers->getCustomers($request);
        
        return response()->json([
                'success' => true,
                'data' => $customers,
            ]);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //Validate data
        $data = $request->only('name', 'address', 'phone', 'nik');
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:20',
            'nik' => 'required|min:16',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ckCustomer = $this->getCustomers->cekCustomers($request, 'byNik', $request->nik);
        if ($ckCustomer) {
            return response()->json([
                'success' => false,
                'message' => 'Customers already exists.',
            ], Response::HTTP_OK);
        }

        $customer = $this->getCustomers->insertCustomers($request);

        if($customer == true){
            return response()->json([
                'success' => true,
                'message' => 'Customer created successfully',
                'data' => $customer
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Failed to add customers.',
            ], 500);
        }
    }

    public function show($id)
    {
        $customer = $this->getCustomers->getCustomersById($id);
    
        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Customer not found.'
            ], 400);
        }else{
            return response()->json([
                'success' => true,
                'data' => $customer
            ], Response::HTTP_OK);
        }
    
        return $customer;
    }

    public function edit(Customer $customer)
    {
        //
    }

    public function update(Request $request, Customer $customer, $id)
    {
        //Validate data
        $data = $request->only('name', 'address', 'phone', 'nik');
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'address' => 'required|max:255',
            'phone' => 'required|max:20',
            'nik' => 'required|min:16',
        ]);

        //Send failed response if request is not valid
        if ($validator->fails()) {
            return response()->json(['error' => $validator->messages()], 200);
        }

        $ckCustomer = $this->getCustomers->cekCustomers($request, 'byId', $id);
        if (!$ckCustomer) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Customer not found.'
            ], 400);
        }
        
        $customer = $this->getCustomers->updateCustomers($request, $id);

        $newcustomer = $this->getCustomers->getCustomersById($id);
        
        //Customer updated, return success response
        if($customer == true){
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully',
                'data' => $newcustomer
            ], Response::HTTP_OK);
        }else{ 
            return response()->json([
                'success' => false,
                'message' => 'Customer not updated'
            ], 500);
        }
    }

    public function destroy(Customer $customer, $id)
    {
        $request    = [];
        $ckCustomer = $this->getCustomers->cekCustomers($request, 'byId', $id);
        if (!$ckCustomer) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, Customer not found.'
            ], 400);
        }

        $customer = $this->getCustomers->deleteCustomers($id);

        if($customer == true){
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully'
            ], Response::HTTP_OK);
        }else{ 
            return response()->json([
                'success' => false,
                'message' => 'Customer not deleted'
            ], 500);
        }
    }
}