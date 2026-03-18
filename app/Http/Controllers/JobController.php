<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Job;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class JobController extends Controller implements HasMiddleware
{
    public static function middleware(): array {
        return [
            new Middleware('permission:view jobs', only:['index']),
            new Middleware('permission:edit jobs', only:['edit']),
            new Middleware('permission:update jobs', only:['update']),
            new Middleware('permission:create jobs', only:['create']),
            new Middleware('permission:delete jobs', only:['destroy']),
        ];
    }
    /**
     * Display a listing of jobs
     */
    public function index()
    {
        $jobs = Job::latest()->paginate(10);
        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show the form for creating a new job
     */
    public function create()
    {
        return view('jobs.create');
    }

    /**
     * Store a newly created job
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'required|in:open,closed'
        ]);

        $imageName = null;

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->storeAs('jobs', $imageName, 'public');
        }

        Job::create([
            'title' => $request->title,
            'description' => $request->description,
            'company' => $request->company,
            'location' => $request->location,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'image' => $imageName,
            'deadline' => $request->deadline,
            'status' => $request->status
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job created successfully.');
    }

    /**
     * Show the form for editing the specified job
     */
    public function edit(Job $job)
    {
        return view('jobs.edit', compact('job'));
    }

    /**
     * Update the specified job
     */
    public function update(Request $request, Job $job)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'company' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'min_salary' => 'nullable|numeric',
            'max_salary' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'required|in:open,closed'
        ]);

        $imageName = $job->image;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($job->image && Storage::disk('public')->exists('jobs/'.$job->image)) {
                Storage::disk('public')->delete('jobs/'.$job->image);
            }

            $file = $request->file('image');
            $imageName = time().'_'.$file->getClientOriginalName();
            $file->storeAs('jobs', $imageName, 'public');
        }

        $job->update([
            'title' => $request->title,
            'description' => $request->description,
            'company' => $request->company,
            'location' => $request->location,
            'min_salary' => $request->min_salary,
            'max_salary' => $request->max_salary,
            'image' => $imageName,
            'deadline' => $request->deadline,
            'status' => $request->status
        ]);

        return redirect()->route('jobs.index')->with('success', 'Job updated successfully.');
    }

    /**
     * Remove the specified job
     */
    public function destroy(string $id)
    {
        $job = job::find($id);

        if (!$job) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.'
            ]);
        }

        // Delete image file if exists
        if ($job->image && Storage::exists('public/jobs/'.$job->image)) {
            Storage::delete('public/jobs/'.$job->image);
        }

        $job->delete();

        return response()->json([
            'status'=> true,
            'message' => 'Job deleted successfully.'
        ]);
    }
}