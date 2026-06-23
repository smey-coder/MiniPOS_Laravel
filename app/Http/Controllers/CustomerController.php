<?php

namespace App\Http\Controllers;

use App\Models\Customers;
use App\Models\Customer_Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class CustomerController extends Controller
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view customers', only:['index']),
            new Middleware('permission:edit customers', only:['edit']),
            new Middleware('permission:update customers', only:['update']),
            new Middleware('permission:create customers', only:['create']),
            new Middleware('permission:delete customers', only:['destroy']),
        ];
    }
    /**
     * Display customers list
     */
    public function index(Request $request)
    {
        try {

            $search = $request->search;

            $customers = Customers::with('customer_type')
                ->when($search, function ($query) use ($search) {

                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%");
                })
                ->latest()
                ->paginate(10)
                ->withQueryString();

            return view('customers.index', compact('customers'));

        } catch (\Exception $e) {

            return back()->with('error', 'Failed to load customers. ' . $e->getMessage());
        }
    }

    /**
     * Show create form
     */
    public function create()
    {
        $customerTypes = Customer_Type::orderBy('name')->get();

        return view('customers.create', compact('customerTypes'));
    }

    /**
     * Store customer
     */
    public function store(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'customer_type_id' => 'required|exists:customer_types,id',
                'name'             => 'required|min:3|max:150',
                'phone'            => 'required|max:20',
                'email'            => 'nullable|email|unique:customers,email',
                'address'          => 'nullable|max:255',
                'city'             => 'nullable|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->route('customers.create')
                    ->withErrors($validator)
                    ->withInput();
            }

            // GET CUSTOMER TYPE
            $customerType = Customer_Type::findOrFail($request->customer_type_id);

            Customers::create([
                'customer_type_id' => $request->customer_type_id,
                'name'             => $request->name,
                'phone'            => $request->phone,
                'email'            => $request->email,
                'address'          => $request->address,
                'city'             => $request->city,

                //AUTO SAVE DISCOUNT RATE
                'discount_rate'    => $customerType->discount_rate,
            ]);

            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer created successfully.');

        } catch (\Exception $e) {

            return back()->with('error', 'Failed to create customer. ' . $e->getMessage());
        }
    }

    /**
     * Show customer details
     */
    public function show(string $id)
    {
        try {

            $customer = Customers::with('customer_type')->findOrFail($id);

            return view('customers.show', compact('customer'));

        } catch (\Exception $e) {

            return redirect()
                ->route('customers.index')
                ->with('error', 'Customer not found.');
        }
    }

    /**
     * Edit customer form
     */
    public function edit(string $id)
    {
        try {

            $customer = Customers::findOrFail($id);
            $customerTypes = Customer_Type::orderBy('name')->get();

            return view('customers.edit', compact('customer', 'customerTypes'));

        } catch (\Exception $e) {

            return redirect()
                ->route('customers.index')
                ->with('error', 'Customer not found.');
        }
    }

    /**
     * Update customer
     */
    public function update(Request $request, string $id)
    {
        try {

            $customer = Customers::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'customer_type_id' => 'required|exists:customer_types,id',
                'name'             => 'required|min:3|max:150',
                'phone'            => 'required|max:20',
                'email'            => 'nullable|email|unique:customers,email,' . $customer->id,
                'address'          => 'nullable|max:255',
                'city'             => 'nullable|max:100',
            ]);

            if ($validator->fails()) {
                return redirect()
                    ->route('customers.edit', $id)
                    ->withErrors($validator)
                    ->withInput();
            }

            // 🔥 GET CUSTOMER TYPE
            $customerType = Customer_Type::findOrFail($request->customer_type_id);

            $customer->update([
                'customer_type_id' => $request->customer_type_id,
                'name'             => $request->name,
                'phone'            => $request->phone,
                'email'            => $request->email,
                'address'          => $request->address,
                'city'             => $request->city,

                // ✅ AUTO UPDATE DISCOUNT RATE
                'discount_rate'    => $customerType->discount_rate,
            ]);

            return redirect()
                ->route('customers.index')
                ->with('success', 'Customer updated successfully.');

        } catch (\Exception $e) {

            return back()->with('error', 'Failed to update customer. ' . $e->getMessage());
        }
    }

    /**
     * Delete customer
     */
    public function destroy(string $id)
    {
        try {

            $customer = Customers::findOrFail($id);
            $customer->delete();

            return response()->json([
                'status'  => true,
                'message' => 'Customer deleted successfully.'
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}