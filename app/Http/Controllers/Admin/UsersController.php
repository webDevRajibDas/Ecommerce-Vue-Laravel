<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $users = User::query()
            ->select('id', 'name', 'email', 'created_at')
            ->when($search, fn($query, $search) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            )
            ->orderBy('id', 'desc')
            ->paginate(50)
            ->withQueryString();
        //dd($users->toArray());
        return Inertia::render('users/Index', [
            'usersData' => $users,
            'filters' => ['search' => $search],
            'totalUsers' => User::count(),
        ]);

    }
}
