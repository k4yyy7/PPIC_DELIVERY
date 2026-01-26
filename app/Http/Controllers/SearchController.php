<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim($request->query('q', ''));
        $users = collect();

        if ($q !== '') {
            $users = User::where('name', 'like', "%{$q}%")
                ->orWhere('email', 'like', "%{$q}%")
                ->paginate(15)
                ->appends(['q' => $q]);
        }

        return view('search.results', compact('users', 'q'));
    }
}
