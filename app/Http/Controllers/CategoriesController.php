<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class CategoriesController extends Controller
{
    // Display categories page
    public function categories()
    {
        $categories = Category::orderBy('created_at', 'desc')->get();
        return view('admin.pages.categories', compact('categories'));
    }

    // Store new category
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'type' => 'required|in:tour,hotel,car,blog',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('categories', 'public');
            }

            Category::create([
                'category' => $request->category,
                'type' => $request->type,
                'image' => $imagePath,
                'status' => true
            ]);

            return redirect()->route('admin.categories')
                ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to create category: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Show edit form
    public function edit($id)
    {
        try {
            $category = Category::findOrFail($id);
            $categories = Category::orderBy('created_at', 'desc')->get();
            return view('admin.pages.categories', compact('categories', 'category'));
        } catch (\Exception $e) {
            return redirect()->route('admin.categories')
                ->with('error', 'Category not found');
        }
    }

    // Update category
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string|max:255',
            'type' => 'required|in:tour,hotel,car,blog',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category = Category::findOrFail($id);
            
            $updateData = [
                'category' => $request->category,
                'type' => $request->type,
            ];

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }
                
                // Store new image
                $updateData['image'] = $request->file('image')->store('categories', 'public');
            }

            $category->update($updateData);

            return redirect()->route('admin.categories')
                ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update category: ' . $e->getMessage())
                ->withInput();
        }
    }

    // Delete category
    public function delete($id)
    {
        try {
            $category = Category::findOrFail($id);
            
            // Check if category has any linked deals
            $dealsCount = $category->deals()->count();
            
            if ($dealsCount > 0) {
                return redirect()->route('admin.categories')
                    ->with('error', 'Cannot delete category. It has ' . $dealsCount . ' deal(s) linked to it. Please remove or reassign the deals first.');
            }
            
            // Delete associated image if exists
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            
            $category->delete();

            return redirect()->route('admin.categories')
                ->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories')
                ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
