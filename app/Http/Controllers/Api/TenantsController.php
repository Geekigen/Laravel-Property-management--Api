<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Tenants;

class TenantsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Cache::remember('tenants_list', 60, function () {
            return Tenants::select('id', 'name', 'email', 'status') // Select only necessary columns
            ->orderBy('name') // Add ordering for consistent results
            ->paginate(10); // Paginate results
        });
        // $tenants = Cache::remember('tenants_list', 60, function () {
        //     return Tenants::paginate(10);
        // });
        // $tenants =  Tenants::all();#without optimization


        return response()->json($tenants, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tenants,email',
            'date_of_birth' => 'required|date',
            'id_number' => 'required|string|unique:tenants,id_number',
            'status' => 'required|string',
        ]);

        $tenant = Tenants::create($validatedData);


        Cache::forget('tenants_list');

        return response()->json($tenant, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tenant = Tenants::find($id);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        return response()->json($tenant, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tenant = Tenants::find($id);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:tenants,email,' . $tenant->id,
            'date_of_birth' => 'sometimes|required|date',
            'id_number' => 'sometimes|required|string|unique:tenants,id_number,' . $tenant->id,
            'status' => 'sometimes|required|string',
        ]);

        $tenant->update($validatedData);


        Cache::forget('tenants_list');

        return response()->json($tenant, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tenant = Tenants::find($id);

        if (!$tenant) {
            return response()->json(['message' => 'Tenant not found'], 404);
        }

        $tenant->delete();


        Cache::forget('tenants_list');

        return response()->json(['message' => 'Tenant deleted successfully'], 200);
    }
}
