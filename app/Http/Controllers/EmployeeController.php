<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Employees;
use App\Models\job;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
class EmployeeController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view employees', only:['index']),
            new Middleware('permission:edit employees', only:['edit']),
            new Middleware('permission:update employees', only:['update']),
            new Middleware('permission:create employees', only:['create']),
            new Middleware('permission:delete employees', only:['destroy']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employees = Employees::orderBy('id', 'ASC')->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $jobs = job::all();
        $users = User::all();

        return view('employees.create', [
            'jobs' => $jobs,
            'users' => $users
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:employees,email',
            'position' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'job_id' => 'nullable|exists:job,id',
            'user_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

       if($validator->fails()){
        return redirect()->route('employees.create')->withInput()->withErrors($validator);
       }

       $employee = new Employees();
       $employee->name = $request->name;
       $employee->email = $request->email;
       $employee->position = $request->position;
       $employee->salary = $request->salary;
       $employee->hire_date = $request->hire_date;
       $employee->status = $request->status;
       $employee->job_id = $request->job_id;
       $employee->user_id = $request->user_id;

       // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.$file->getClientOriginalName();
            $file->storeAs('employees', $filename, 'public');
            $employee->image = $filename;
        }

        $employee->save();

        Session::flash('success','Employee added successfully.');
        return redirect()->route('employees.index');

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $employee = Employees::findOrFail($id);
        $jobs = job::pluck('title', 'id');
        $users = User::pluck('name', 'id');

        return view('employees.edit', compact('employee', 'jobs', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $employee = Employees::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:employees,email,'.$id,
            'position' => 'nullable|string',
            'salary' => 'nullable|numeric',
            'hire_date' => 'nullable|date',
            'status' => 'required|in:active,inactive',
            'job_id' => 'nullable|exists:job,id',
            'user_id' => 'nullable|exists:users,id',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048'
        ]);

        if ($validator->passes()) {

            $imageName = $employee->image;
            if ($request->hasFile('image')) {
                if ($employee->image && Storage::disk('public')->exists('employees/'.$employee->image)) {
                    Storage::disk('public')->delete('employees/'.$employee->image);
                }
                $file = $request->file('image');
                $imageName = time().'_'.$file->getClientOriginalName();
                $file->storeAs('employees', $imageName, 'public');
            }

            try {
                $employee->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'position' => $request->position,
                    'salary' => $request->salary,
                    'hire_date' => $request->hire_date,
                    'status' => $request->status,
                    'job_id' => $request->job_id,
                    'user_id' => $request->user_id,
                    'image' => $imageName
                ]);
            } catch(\Illuminate\Database\QueryException $e){
                return redirect()->back()->withInput()->withErrors(['salary'=>$e->getMessage()]);
            }

            return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');

        } else {
            return redirect()->route('employees.edit', $id)->withInput()->withErrors($validator);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $employee = Employees::find($id);

        if(!$employee){
            return response()->json(['status'=>false,'message'=>'Employee not found']);
        }

        if ($employee->image && Storage::disk('public')->exists('employees/'.$employee->image)) {
            Storage::disk('public')->delete('employees/'.$employee->image);
        }

        $employee->delete();

        return response()->json(['status'=>true,'message'=>'Employee deleted successfully']);
    }
}