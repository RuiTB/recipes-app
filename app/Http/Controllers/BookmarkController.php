<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookmarkController extends Controller
{

    public function index()
    {
        $bookmarks = Auth::user()->bookmarks;
        return response()->json($bookmarks, 200);
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|min:4'
        ]);

        $bookmark = new Bookmark();
        $bookmark->name = $request->input('name');
        $bookmark->user_id = Auth::user()->id;
        $bookmark->save();

        return response()->json($bookmark, 201);
    }


    public function show(Bookmark $bookmark)
    {
        if (!$bookmark->isOwnedByUser(auth()->user()->id)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return response()->json($bookmark, 200);
    }


    public function update(Request $request, Bookmark $bookmark)
    {
        $this->validate($request, [
            'name' => 'required|min:4'
        ]);

        if ($bookmark->isOwnedByUser(auth()->user()->id)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }


        $bookmark->name = $request->input('name');
        $bookmark->save();

        return response()->json($bookmark, 200);
    }


    public function destroy(Bookmark $bookmark)
    {
        if ($bookmark->isOwnedByUser(auth()->user()->id)) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $bookmark->delete();

        return response()->json(['message' => 'Bookmark deleted'], 200);
    }
}
