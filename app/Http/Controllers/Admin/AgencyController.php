<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function index()
    {
        // Global access
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $agencies = \App\Models\Agency::all();
        return view('admin.agencies.index', compact('agencies'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        if (!auth()->user()->isSuperAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:agencies',
            'subdomain' => 'nullable|string|unique:agencies',
        ]);

        \App\Models\Agency::create($validated);

        return redirect()->route('admin.agencies.index');
    }
}