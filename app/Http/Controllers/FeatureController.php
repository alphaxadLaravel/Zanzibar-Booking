<?php

namespace App\Http\Controllers;

use App\Models\Features;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeatureController extends Controller
{
    protected function featuresQuery(Request $request)
    {
        $search = trim((string) $request->get('search', ''));

        $query = Features::query()
            ->orderByDesc('status')
            ->orderByDesc('id');

        if ($search !== '') {
            $query->where(function ($builder) use ($search) {
                $builder->where('name', 'like', '%' . $search . '%')
                    ->orWhere('type', 'like', '%' . $search . '%');
            });
        }

        return $query;
    }

    /**
     * Display a listing of features
     */
    public function index(Request $request)
    {
        $features = $this->featuresQuery($request)->paginate(20)->withQueryString();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'html' => view('admin.partials.features_list', compact('features'))->render(),
                'total' => $features->total(),
                'from' => $features->firstItem() ?? 0,
                'to' => $features->lastItem() ?? 0,
            ]);
        }

        return view('admin.pages.features', compact('features'));
    }

    public function checkName(Request $request)
    {
        $name = trim((string) $request->get('name', ''));
        if ($name === '') {
            return response()->json(['exists' => false, 'feature' => null]);
        }

        $feature = Features::query()
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->first();

        return response()->json([
            'exists' => (bool) $feature,
            'feature' => $feature ? [
                'id' => $feature->id,
                'name' => $feature->name,
                'type' => $feature->type,
            ] : null,
        ]);
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
            'status' => $request->boolean('status', true),
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
            'status' => $request->boolean('status', $feature->status),
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
    public function toggleStatus(Request $request, $id)
    {
        $feature = Features::findOrFail($id);
        $feature->update(['status' => !$feature->status]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'status' => (bool) $feature->status,
                'message' => $feature->status ? 'Feature activated.' : 'Feature deactivated.',
            ]);
        }

        $status = $feature->status ? 'activated' : 'deactivated';
        return redirect()->route('admin.features')
            ->with('success', "Feature {$status} successfully!");
    }
}
