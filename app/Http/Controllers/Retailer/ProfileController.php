<?php

namespace App\Http\Controllers\Retailer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $retailer = $user->retailer;

        if (!$retailer) {
            return redirect()->route('dashboard')->with('error', 'Retailer profile not found.');
        }

        return view('retailer.profile.edit', compact('retailer'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $retailer = $user->retailer;

        if (!$retailer) {
            return redirect()->route('dashboard')->with('error', 'Retailer profile not found.');
        }

        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'address' => 'required|string',
            'business_type' => 'nullable|string|max:255',
            'preferred_delivery_method' => 'nullable|in:pickup,delivery,both',
        ]);

        $retailer->update($validated);

        return redirect()->route('retailer.profile.edit')->with('success', 'Profile updated successfully.');
    }
}
