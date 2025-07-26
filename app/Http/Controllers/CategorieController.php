<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB; // <-- Add this line

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource with filtering, ordered by name.
     */
    public function index(Request $request)
    {
        $query = Categorie::query();

        if ($request->filled('id')) {
            $query->where('id', $request->id);
        }
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        // MODIFIED: Changed latest() to orderBy('name')
        $categories = $query->orderBy('name', 'asc')->paginate(10)->appends($request->query());

        return view('categories.index', compact('categories'));
    }

    /**
     * Store one or more newly created resources in storage.
     */
    public function store(Request $request)
    {
        // MODIFIED: Validate an array of names
        $validated = $request->validate([
            'names' => 'required|array|min:1',
            'names.*' => 'required|string|max:255|unique:categories,name',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['names'] as $name) {
                if (!empty($name)) {
                    Categorie::create(['name' => $name]);
                }
            }
        });

        return redirect()->route('categories.index')->with('success', 'Categories created successfully.');
    }

    // ... The rest of your controller (edit, update, destroy) is fine ...
    
    public function edit($id)
    {
        $categorie = Categorie::find($id);
        return view('categories.edit', compact('categorie'));
    }
 
    public function update($id, Request $request)
    {
        $categorie = Categorie::find($id);
        $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $categorie->id,
        ]);
        $categorie->name = $request->name;
        $categorie->save();
        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy($id)
    {
        $categorie = Categorie::find($id);
        $categorie->delete();
        return redirect()->route('categories.index')->with('success', 'Category has been deleted.');
    }
}