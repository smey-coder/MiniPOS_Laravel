<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class PermissionController extends Controller implements HasMiddleware
{
    /**
     * Display a listing of the resource.
     */

    public static function middleware(): array {
        return [
            new Middleware('permission:view permissions', only:['index']),
            new Middleware('permission:edit permissions', only:['edit']),
            new Middleware('permission:update permissions', only:['update']),
            new Middleware('permission:create permissions', only:['create']),
            new Middleware('permission:delete permissions', only:['destroy']),
        ];
    }
    public function index()
    {
        $permissions = Permission::orderBy('created_at', 'DESC')->paginate(10);
        return view('permissions.index', [
            'permissions' => $permissions
        ]
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|unique:permissions|min:3'
        ]);

        if($validator->passes()) {
            Permission::create(['name' =>$request->name]);
            return redirect()->route('permissions.index')->with('success', 'Permission Created successfully.');
        }else {
            return redirect()->route('permissions.create')->withInput()->withErrors($validator);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $permission = Permission::findOrFail($id);
        return view('permissions.edite', [
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $permission = Permission::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|unique:permissions,name,'.$id.',id' 
        ]);
        if($validator->passes()) {
            $permission->name = $request->name;
            $permission->save();

            return redirect()->route('permissions.index')->with('success', 'Permission Updated successfully.');
        }else{
            return redirect()->route('permisions.edit', $id)->withInput()->withErros($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $permission = Permission::find($id);

        if($permission == null){
            session()->flash('error','Permission not found');
            return response()->json([
                'status' => false
            ]);
        }
        $permission->delete();

        session()->flash('success', 'Permission deleted successfully.');

        return response()->json([
            'status' =>true
        ]);
    }
}
