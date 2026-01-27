<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Brand::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:brands,slug',
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $brand = Brand::create($validated);
        return response()->json($brand, 201);
    }

    public function show(Brand $brand)
    {
        return response()->json($brand);
    }

    public function update(Request $request, Brand $brand)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:brands,slug,' . $brand->id,
            'logo' => 'nullable|string',
            'description' => 'nullable|string',
            'status' => 'nullable|in:active,inactive',
        ]);

        $brand->update($validated);
        return response()->json($brand);
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return response()->json(null, 204);
    }
}
