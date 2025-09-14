<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            Category::create([
                'category' => $request->category,
                'type' => $request->type,
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
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $category = Category::findOrFail($id);
            $category->update([
                'category' => $request->category,
                'type' => $request->type,
            ]);

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
            $category->delete();

            return redirect()->route('admin.categories')
                ->with('success', 'Category deleted successfully');
        } catch (\Exception $e) {
            return redirect()->route('admin.categories')
                ->with('error', 'Failed to delete category: ' . $e->getMessage());
        }
    }
}
