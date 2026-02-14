<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Http\Traits\FilterableController;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use FilterableController;

    public function index(Request $request)
    {
        $agencyId = auth()->user()->agency_id;

        $query = \App\Models\User::where('agency_id', $agencyId);

        // Filters
        $filterConfig = [
            [
                'key' => 'search',
                'type' => 'search',
                'label' => 'Search',
                'fields' => ['name', 'email'],
            ],
            [
                'key' => 'role',
                'type' => 'select',
                'label' => 'Role',
                'field' => 'role',
                'options' => [
                    'agency_admin' => 'Admin',
                    'agent' => 'Agent',
                ],
            ],
        ];

        // Sort Config
        $sortConfig = [
            'default' => 'created_at',
            'options' => [
                ['field' => 'name', 'label' => 'Name'],
                ['field' => 'email', 'label' => 'Email'],
                ['field' => 'role', 'label' => 'Role'],
                ['field' => 'created_at', 'label' => 'Date Joined'],
            ],
        ];

        $query = $this->applyFilters($query, $request, $filterConfig);
        $query = $this->applySorting($query, $request, $sortConfig);

        $users = $query->paginate(15)->withQueryString();

        $currentFilters = $request->only(['search', 'role']);
        $currentSort = [
            'field' => $request->input('sort_by', 'created_at'),
            'direction' => $request->input('sort_direction', 'desc'),
        ];
        $activeCount = $this->getActiveFiltersCount($request, $filterConfig);

        return view('users.index', compact('users', 'filterConfig', 'sortConfig', 'currentFilters', 'currentSort', 'activeCount'));
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