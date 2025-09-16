<?php

namespace App\Http\Controllers;

use App\Models\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
    /**
     * Display a listing of features
     */
    public function index()
    {
        $features = Features::orderBy('created_at', 'desc')->get();
        return view('admin.pages.features', compact('features'));
    }


    /**
     * Store a newly created feature
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:features,name',
            'icon' => 'required|string|max:255',
            'type' => 'required|string|in:hotel,include,exclude,car,apartment,tour',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        Features::create([
            'name' => $request->name,
            'icon' => $request->icon,
            'type' => $request->type,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('admin.features')
            ->with('success', 'Feature created successfully!');
    }


    /**
     * Update the specified feature
     */
    public function update(Request $request, $id)
    {
        $feature = Features::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:features,name,' . $id,
            'icon' => 'required|string|max:255',
            'type' => 'required|string|in:hotel,include,exclude,car,apartment,tour',
            'status' => 'boolean'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $feature->update([
            'name' => $request->name,
            'icon' => $request->icon,
            'type' => $request->type,
            'status' => $request->has('status') ? 1 : 0
        ]);

        return redirect()->route('admin.features')
            ->with('success', 'Feature updated successfully!');
    }

    /**
     * Remove the specified feature
     */
    public function destroy($id)
    {
        $feature = Features::findOrFail($id);
        $feature->delete();

        return redirect()->route('admin.features')
            ->with('success', 'Feature deleted successfully!');
    }

    /**
     * Toggle feature status
     */
    public function toggleStatus($id)
    {
        $feature = Features::findOrFail($id);
        $feature->update(['status' => !$feature->status]);

        $status = $feature->status ? 'activated' : 'deactivated';
        return redirect()->route('admin.features')
            ->with('success', "Feature {$status} successfully!");
    }
}
