<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use App\Models\Product_Types;
use App\Models\Suppliers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index()
    {
        $products = Products::with(['productType', 'supplier'])
                            ->orderBy('id', 'ASC')
                            ->paginate(10);

        return view('products.index', [
            'products' => $products
        ]);
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $productTypes = Product_Types::all();
        $suppliers = Suppliers::all();

        return view('products.create', [
            'Product_Types' => $productTypes,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Store a newly created product.
     */
    public function store(Request $request)
    {
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
    }

    /**
     * Show the form for editing a product.
     */
    public function edit(string $id)
    {
        $product = Products::findOrFail($id);
        $productTypes = Product_Types::all();
        $suppliers = Suppliers::all();

        return view('products.edit', [
            'product' => $product,
            'Product_Types' => $productTypes,
            'suppliers' => $suppliers
        ]);
    }

    /**
     * Update the specified product.
     */
    public function update(Request $request, string $id)
    {
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
    }

    /**
     * Remove the specified product.
     */
    public function destroy(string $id)
    {
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
    }
}