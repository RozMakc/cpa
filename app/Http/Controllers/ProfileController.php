<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\PaymentMethod;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = Auth::user();
        $user->load(['documents', 'paymethods']);
        
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $user,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    public function generateNewToken(Request $request)
    {
        $user = $request->user();
        $user->tokens()->delete();
        $tokenResult = $user->createToken('api');
        $plainTextToken = $tokenResult->plainTextToken;

        $user->apikey = $plainTextToken;
        $user->save();
        
        return back();
    }

    public function paymethod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'payment_method' => 'sometimes|nullable|array',
            'payment_method.bank_name' => 'sometimes|nullable|string|max:20',
            'payment_method.bank_bik' => 'sometimes|nullable|string|min:5',
            'payment_method.bank_rs' => 'sometimes|nullable|string|min:10'
        ]);

        $user = Auth::user();

        if (isset($validated['payment_method'])) {
            $user->paymethods()->updateOrCreate(
                ['user_id' => $user->id],
                $validated['payment_method']
            );
        }

        return Redirect::route('profile.edit');
    }

    public function documents(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'documents' => 'sometimes|nullable|array',
            'documents.inn' => 'sometimes|nullable|string|max:20',
            'documents.pasport_birthplace' => 'sometimes|nullable|string|max:255',
            'documents.pasport_series' => 'sometimes|nullable|string|max:10',
            'documents.pasport_number' => 'sometimes|nullable|string|max:20',
            'documents.pasport_who' => 'sometimes|nullable|string|max:255',
            'documents.pasport_when' => 'sometimes|nullable|date',
            'documents.pasport_code' => 'sometimes|nullable|string|max:10'
        ]);

        $user = Auth::user();

        if (isset($validated['documents'])) {
            $user->documents()->updateOrCreate(
                ['user_id' => $user->id],
                $validated['documents']
            );
        }

        return Redirect::route('profile.edit');
    }
}
