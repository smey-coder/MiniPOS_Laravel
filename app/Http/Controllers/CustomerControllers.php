<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CustomerControllers extends Controller implements HasMiddleware
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
     * Display a listing of customers
     */
    public function index()
    {
        $customers = Customers::orderBy('id','DESC')->paginate(10);

        return view('customers.index',[
            'customers' => $customers
        ]);
    }


    /**
     * Show create form
     */
    public function create()
    {
        return view('customers.create');
    }


    /**
     * Store customer
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'clientType' => 'required|min:3|max:50',
            'name' => 'required|min:3|max:150',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|unique:customers,email',
            'address' => 'nullable|min:5',
            'city' => 'nullable|min:3|max:100',
            'discount' => 'nullable|numeric|min:0'
        ]);

        if($validator->passes()){

            Customers::create([
                'clientType' => $request->clientType,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'discount' => $request->discount
            ]);

            return redirect()->route('customers.index')
                    ->with('success','Customer created successfully.');

        }else{

            return redirect()->route('customers.create')
                    ->withInput()
                    ->withErrors($validator);

        }
    }


    /**
     * Edit customer form
     */
    public function edit(string $id)
    {
        $customer = Customers::findOrFail($id);

        return view('customers.edit',[
            'customer' => $customer
        ]);
    }


    /**
     * Update customer
     */
    public function update(Request $request, string $id)
    {

        $customer = Customers::findOrFail($id);

        $validator = Validator::make($request->all(),[
            'clientType' => 'required|min:3|max:50',
            'name' => 'required|min:3|max:150',
            'phone' => 'required|max:20',
            'email' => 'nullable|email|unique:customers,email,'.$customer->id,
            'address' => 'nullable|min:5',
            'city' => 'nullable|min:3|max:100',
            'discount' => 'nullable|numeric|min:0'
        ]);

        if($validator->passes()){

            $customer->update([
                'clientType' => $request->clientType,
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'address' => $request->address,
                'city' => $request->city,
                'discount' => $request->discount
            ]);

            return redirect()->route('customers.index')
                    ->with('success','Customer updated successfully.');

        }else{

            return redirect()->route('customers.edit',$id)
                    ->withInput()
                    ->withErrors($validator);
        }

    }


    /**
     * Delete customer
     */
    public function destroy(string $id)
    {

        $customer = Customers::find($id);

        if($customer == null){
            return response()->json([
                'status' => false
            ]);
        }

        $customer->delete();

        return response()->json([
            'status' => true
        ]);
    }

}