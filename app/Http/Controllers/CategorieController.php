<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;

class CategorieController extends Controller
{
    public function index()
    {
        // WRONG WAY (This returns a Collection)
        $categories = Categorie::latest()->paginate(10);
        // OR

        return view('categories.index', compact('categories'));

    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        Categorie::create($validated);

        return redirect()->route('categories.index')->with('success', 'Categories created successfully.');
    }

    // Edit vendor
    public function edit($id)
    {
        $categorie = Categorie::find($id);
        //return $employee;
        return view('categories.edit', compact('categorie'));
    }


    //update file   
    public function update($id, Request $request)
    {
        //return $request->all();
        $categorie = Categorie::find($id);
        $categorie->name = $request->name;

        $categorie->save();
        return redirect()->route('categories.index')->with('success', 'Categories update successfully.');
    }


    //Delete file
    public function destroy($id)
    {
        $categorie = Categorie::find($id);
        $categorie->delete();
        return redirect()->route('categories.index')->with('success', 'Categories has been deleted.');
    }

}
