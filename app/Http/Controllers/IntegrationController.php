<?php

namespace App\Http\Controllers;

use App\Models\Integration;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class IntegrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $integrations = Integration::all();
        return Inertia::render('Admin/Integrations/Index', [
            'integrations' => $integrations,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Integrations/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $integration = new Integration();
        $integration->name = $validated['name'];
        $integration->apikey = Str::random(25);
        $integration->save();

        
        return redirect()->route('integration.index')
            ->with('success', 'Offer created successfully.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Integration $integration)
    {
        return Inertia::render('Admin/Integrations/Edit', [
            'integrations' => $integration,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Integration $integration)
    {
        
        return Inertia::render('Admin/Integrations/Edit', [
            'integration' => $integration,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Integration $integration)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $integration->name = $validated['name'];
        $integration->save();

        return redirect()->back();
    }

    public function newkey(Request $request, Integration $integration)
    {
        $integration->apikey = Str::random(25);
        $integration->save();

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Integration $integration)
    {
        $integration->delete();
        return redirect()->back();
    }
}
