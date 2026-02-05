<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index()
    {
        $agencyId = auth()->user()->agency_id;
        $users = \App\Models\User::where('agency_id', $agencyId)->get();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:agency_admin,agent',
        ]);

        \App\Models\User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
            'agency_id' => auth()->user()->agency_id,
            'role' => $validated['role'],
        ]);

        return redirect()->route('dashboard.users.index');
    }
}
