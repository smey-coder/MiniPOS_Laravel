<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Product_Types;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class ProductController extends Controller implements HasMiddleware
{

    //Manager Permission of the Products
    public static function middleware(): array {
        return [
            new Middleware('permission:view products', only:['index']),
            new Middleware('permission:create products', only:['create']),
            new Middleware('permission:edit products', only:['edit']),
            new Middleware('permission:update products', only:['update']),
            new Middleware('permission:delete products', only:['destroy']),
        ];
    }
    /**
     * Display a listing of products.
     */
    public function index()
    {
        try{
            $products = Products::with(['productType', 'supplier'])
            ->orderBy('id', 'ASC')
            ->paginate(10);
            return view('products.index', ['products' => $products]);

        }catch (\Exception $e){
             return back()->with(
            'error',
            'Failed to load create form. '.$e->getMessage()
        );

        }

    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        try{

            $productTypes = Product_Types::all();
            $suppliers = Suppliers::all();

            return view('products.create', [
                'Product_Types' => $productTypes,
                'suppliers' => $suppliers
            ]);
            
        }catch (\Exception $e){
             return back()->with(
            'error',
            'Failed to load create form. '.$e->getMessage());
        }


    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
        try{
                $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'barcode' => 'nullable|string|max:255',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'product_type_id' => 'nullable|exists:product_type,id',
                'supplier_id' => 'nullable|exists:supplier,id',
                'cost_price' => 'required|numeric|min:0',
                'sell_price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'description' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return redirect()->route('products.create')->withInput()->withErrors($validator);
            }

            $product = new Products();
            $product->name = $request->name;
            $product->barcode = $request->barcode;
            $product->product_type_id = $request->product_type_id;
            $product->supplier_id = $request->supplier_id;
            $product->description = $request->description;
            $product->cost_price = $request->cost_price;
            $product->sell_price = $request->sell_price;
            $product->quantity = $request->quantity;

            // Handle image upload
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $filename = time().'_'.$file->getClientOriginalName();
                $file->storeAs('products', $filename, 'public');
                $product->image = $filename;
            }

            $product->save();

            Session::flash('success','Product added successfully.');
            return redirect()->route('products.index');
        }catch (\Exception $e){
             return back()->with(
            'error',
            'Failed to load create form. '.$e->getMessage());
        }
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(string $id)
    {
        try{

        $product = Products::findOrFail($id);
        $productTypes = Product_Types::all();
        $suppliers = Suppliers::all();

        return view('products.edit', [
            'product' => $product,
            'Product_Types' => $productTypes,
            'suppliers' => $suppliers
        ]);

        }catch (\Exception $e){
            return back()->with(
                'error',
                'Failed to load edit form.'.$e->getMessage()
            );
        }
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id)
    {
        try{
            $product = Products::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'product_type_id' => 'nullable|exists:product_type,id',
            'supplier_id' => 'nullable|exists:supplier,id',
            'cost_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('products.edit', $id)
                             ->withErrors($validator)
                             ->withInput();
        }

        $product->name = $request->name;
        $product->barcode = $request->barcode;
        $product->product_type_id = $request->product_type_id;
        $product->supplier_id = $request->supplier_id;
        $product->description = $request->description;
        $product->cost_price = $request->cost_price;
        $product->sell_price = $request->sell_price;
        $product->quantity = $request->quantity;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists('products/'.$product->image)) {
                Storage::disk('public')->delete('products/'.$product->image);
            }

            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('products', $filename, 'public');
            $product->image = $filename;
        }

        $product->save();

        Session::flash('success','Product updated successfully.');

        return redirect()->route('products.index');

        }catch (\Exception $e){
            return back()->with(
                'error',
                'Failed to load edit form.'.$e->getMessage()
            );
        }
        
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id)
    { 
        
        try{
            $product = Products::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ]);
        }

        // Delete image file if exists
        if ($product->image && Storage::exists('public/products/'.$product->image)) {
            Storage::delete('public/products/'.$product->image);
        }

        $product->delete();

        return response()->json([
            'status'=> true,
            'message' => 'Product deleted successfully.'
        ]);
        }catch (\Exception $e){
            return back()->with(
                'error',
                'Failed to load edit form.'.$e->getMessage()
            );
        }

    }
    /**
     * Display product detail
     */
    public function show(string $id)
    {
        try {

            $product = Products::with(['productType', 'supplier'])
                ->findOrFail($id);

            return view('products.show', compact('product'));

        } catch (\Exception $e) {

            return back()->with(
                'error',
                'Product not found or failed to load. ' . $e->getMessage()
            );
        }
    }
}