<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{

    public function index(Request $request)
    {
        $query = Recipe::query();

        if ($request->has('ingredient')) {
            $query->whereHas('ingredients', function ($query) use ($request) {
                $query->where('slug', $request->input('ingredient'));
            });
        }

        return $query->paginate(6);
    }


    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|min:4',
            'description' => 'required|min:10',
            'steps' => 'required|integer',
            'time' => 'required|integer',
            'size' => 'required',
            'ingredients' => 'required|array',
            'ingredients.*' => 'distinct|exists:ingredients,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $validatedData['user_id'] = auth()->user()->id;

        $recipe = Recipe::create($validatedData);

        if ($request->hasFile('photo')) {
            $photoFile = $request->file('photo');
            $photoPath = $photoFile->store('recipes');
            $recipe->photo = $photoPath;
        }

        $recipe->save();

        $recipe->ingredients()->attach($validatedData['ingredients']);

        return response()->json($recipe, 201);
    }




    public function show(Recipe $recipe)
    {
        $recipe->load('comments', 'ingredients');
        return response()->json($recipe, 200);
    }


    public function update(Request $request, Recipe $recipe)
    {
        $validatedData = $request->validate([
            'title' => 'min:4',
            'description' => 'min:10',
            'steps' => 'integer',
            'time' => 'date_format:H:i:s',
            'size',
            'ingredients' => 'array',
            'ingredients.*' => 'distinct|exists:ingredients,id'
        ]);

        if (!$recipe->isOwnedByUser(auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($request->hasFile('photo')) {
            Storage::delete($recipe->photo);
            $photoFile = $request->file('photo');
            $validatedData['photo'] =  $photoFile->storeAs('/recipes', $photoFile->getClientOriginalName());
        }

        $recipe->update($validatedData);
        $recipe->ingredients()->sync($validatedData['ingredients']);

        return response()->json($recipe, 200);
    }


    public function destroy(Recipe $recipe)
    {
        if (!$recipe->isOwnedByUser(auth()->user())) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        Storage::delete($recipe->photo);
        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted'], 200);
    }


    public function comment(Request $request, Recipe $recipe)
    {
        $validatedData = $request->validate([
            'content' => 'required'
        ]);

        $validatedData['user_id'] = auth()->user()->id;
        $validatedData['recipe_id'] = $recipe->id;

        $comment = Comment::create($validatedData);

        return response()->json($comment, 201);
    }
}
