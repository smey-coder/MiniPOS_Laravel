<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller implements HasMiddleware


{
    /**
     * Display a listing of the resource.
     */
    public static function middleware(): array {
        return [
            new Middleware('permission:view users', only:['index']),
            new Middleware('permission:create users', only:['create']),
            new Middleware('permission:edit users', only:['edit']),
            new Middleware('permission:update users', only:['update']),
            new Middleware('permission:delete users', only:['destroy']),
        ];
    }
    public function index()
    {
        $users = User::latest()->paginate(10);
        return view('users.index', [
            'users' =>$users
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::orderBy('name', 'ASC')->get();
        return view('users.create', [
            'roles' => $roles
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, )
    {
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8|same:confirm_password',
            'confirm_password' => 'required',
        ]);

        if($validator->fails()) {
            return redirect()->route('users.create')->withInput()->withErrors($validator);
        }
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User added successfully.');
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
        $users = User::findOrFail($id);
        $roles = Role::orderBy('name', 'ASC')->get();

        $hasRoles = $users->roles->pluck('id');
        // dd($hasRoles);
        return view('users.edit', [
            'users' => $users,
            'roles' => $roles,
            'hasRoles' => $hasRoles
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);
        $validator = Validator::make($request->all(),[
            'name' => 'required|min:3',
            'email' => 'required|unique:users,email, '.$id.',id'
        ]);

        if($validator->fails()) {
            return redirect()->route('users.edit', $id)->withInput()->withErrors($validator);
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save;

        $user->syncRoles($request->role);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if($user == null){
            return response()->json([
                'status' => false
            ]);
        }

        $user->delete();

        session()->flash('success', 'User Deleted successfully.');
        return response()->json([
            'status'=> true
        ]);
    }
}
