<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::all();
        return response()->json(['data' => $customers], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:50',
            'last_name' => 'required|max:50',
            'age' => 'required|integer',
            'dob' => 'required|date',
            'email' => 'required|email|max:100|unique:customers',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $customer = Customer::create($request->all());
        return response()->json(['data' => $customer], 201);
    }

    public function show($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        return response()->json(['data' => $customer], 200);
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|max:50',
            'last_name' => 'sometimes|max:50',
            'age' => 'sometimes|integer',
            'dob' => 'sometimes|date',
            'email' => 'sometimes|email|max:100|unique:customers,email,'.$id,
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $customer->update($request->all());
        return response()->json(['data' => $customer], 200);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if (!$customer) {
            return response()->json(['error' => 'Customer not found'], 404);
        }
        $customer->delete();
        return response()->json(['message' => 'Customer deleted successfully'], 200);
    }
}