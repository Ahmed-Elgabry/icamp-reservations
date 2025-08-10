<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomersController extends Controller
{
    public function index()
    {
        $query = Customer::query();
        $request = request();

        // Check if the search parameters are set and add them to the query
        if ($request->has('name') && !empty($request->name)) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        if ($request->has('email') && !empty($request->email)) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }

        if ($request->has('phone') && !empty($request->phone)) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }

        $customers = $query->paginate(100);

        return view('dashboard.customers.index', compact('customers'));
    }

    // Show the form for creating a new customer
    public function create()
    {
        return view('dashboard.customers.create');
    }

    // Store a newly created customer in the database
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:customers',
            'phone' => 'nullable|unique:customers',
            'notes' => 'nullable',
        ]);
        try {
            \DB::beginTransaction();
            Customer::create($validatedData);
            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json($e->getMessage(), 500);
        }
        return response()->json();
    }

    // Display the specified customer
    public function show( $customer)
    {
        $customer = Customer::findOrFail($customer);
        return view('dashboard.customers.show', compact('customer'));
    }

    // Show the form for editing the specified customer
    public function edit( $customer)
    {
        $customer = Customer::findOrFail($customer);
        return view('dashboard.customers.create', compact('customer'));
    }

    // Update the specified customer in the database
    public function update(Request $request,  $customer)
    {
        $customer = Customer::findOrFail($customer);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|unique:customers,phone,' . $customer->id,
            'notes' => 'nullable',
        ]);

        $customer->update($validatedData);
        return response()->json();
    }

    // Remove the specified customer from the database
    public function destroy( $customer)
    {
        $customer = Customer::findOrFail($customer);
        $customer->delete();
        return response()->json();
    }

    public function deleteAll(Request $request) {
        $requestIds = json_decode($request->data);

        foreach ($requestIds as $id) {
          $ids[] = $id->id;
        }
        if (Customer::whereIn('id', $ids)->delete()) {
          return response()->json('success');
        } else {
          return response()->json('failed');
        }
    }
}
