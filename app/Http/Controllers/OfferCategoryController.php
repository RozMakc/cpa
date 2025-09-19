<?php

namespace App\Http\Controllers;

use App\Models\OfferCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OfferCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = OfferCategory::all();
        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $category = new OfferCategory();
        $category->name = $validated['name'];
        $category->save();

        
        return redirect()->route('offerCategory.index')
            ->with('success', 'Offer created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OfferCategory $offerCategory)
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $offerCategory,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OfferCategory $offerCategory)
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => $offerCategory,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OfferCategory $offerCategory)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $offerCategory->name = $validated['name'];
        $offerCategory->save();
        return redirect()->route('offerCategory.index')
            ->with('success', 'Offer created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OfferCategory $offerCategory)
    {
        $offerCategory->delete();
        return redirect()->back();
    }
}
