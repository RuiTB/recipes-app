<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class IngredientController extends Controller
{

    public function index()
    {
        $ingredients = Ingredient::all();
        return response()->json($ingredients, 200);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:6',
            'slug' => 'nullable'
        ]);

        if (!isset($validatedData['slug'])) {
            $validatedData['slug'] = strtolower(str_replace(' ', '-', $validatedData['name']));
        }

        $ingredient = Ingredient::create($validatedData);
        return response()->json($ingredient, 201);
    }


    public function show(Ingredient $ingredient)
    {
        return response()->json($ingredient, 200);
    }


    public function update(Request $request, Ingredient $ingredient)
    {
        $validatedData = $request->validate([
            'name' => 'required|min:6',
            'slug' => 'nullable'
        ]);

        if (!isset($validatedData['slug'])) {
            $validatedData['slug'] = strtolower(str_replace(' ', '-', $validatedData['name']));
        }

        $ingredient->update($validatedData);
        return response()->json($ingredient, 200);
    }


    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return response()->json(['message' => 'Ingredient deleted'], 200);
    }


    public function recipes(Ingredient $ingredient)
    {
        $recipes = $ingredient->recipes;
        return response()->json($recipes, 200);
    }
}
